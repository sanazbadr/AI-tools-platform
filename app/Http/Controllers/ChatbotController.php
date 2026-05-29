<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\Conversation;
use App\Models\Message;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

    private $apiKey;
    private $conversationContext = [];
    private $maxContextLength = 10;
    private const ERROR_QUOTA = 'INTERNAL_AI_ERROR_QUOTA';
    private const ERROR_INTERNAL = 'INTERNAL_AI_ERROR';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function getConversations()
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return response()->json(['success' => false, 'error' => 'User not authenticated'], 401);
        }

        $conversations = DB::table('chat_history')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get(['url', 'title']);
            
        return response()->json(['success' => true, 'conversations' => $conversations]);
    }

    public function getMessages(Request $request)
    {
        $request->validate(['conversation_url' => 'required|string']);
        
        $userId = Session::get('user_id');
        if (!$userId) {
            return response()->json(['success' => false, 'error' => 'User not authenticated'], 401);
        }

        // First check if the conversation belongs to the user
        $conversation = DB::table('chat_history')
            ->where('url', $request->conversation_url)
            ->where('user_id', $userId)
            ->first();

        if (!$conversation) {
            return response()->json(['success' => false, 'error' => 'Conversation not found'], 404);
        }

        // Get messages with type field and ensure no duplicates
        $messages = DB::table('messages')
            ->where('conversation_url', $request->conversation_url)
            ->orderBy('created_at', 'asc')
            ->get(['role', 'content', 'type'])
            ->unique(function ($message) {
                return $message->role . $message->content;
            })
            ->values()
            ->map(function ($message) {
                // Format the content for consistent spacing
                $content = $message->content;
                
                // Ensure proper spacing after numbers and headings
                $content = preg_replace('/(\d+\.)\s*([A-Za-z])/', "$1\n\n$2", $content);
                
                // Ensure proper spacing before bullet points
                $content = preg_replace('/([A-Za-z])\n\s*-/', "$1\n\n-", $content);
                
                $message->content = $content;
                return $message;
            });

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function generateUrl(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        try {
            $userId = Session::get('user_id');
            if (!$userId) {
                return response()->json(['success' => false, 'error' => 'User not authenticated'], 401);
            }

            // Generate a unique conversation URL
            $conversationId = md5(time() . rand());
            $conversationUrl = '/chatbot/' . $conversationId;

            // Generate a conversation title
            $title = $this->generateTitle($request->message);

            // Save to the database with user_id
            DB::table('chat_history')->insert([
                'url' => $conversationUrl,
                'title' => $title,
                'user_id' => $userId,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'conversation_url' => $conversationUrl,
                'title' => $title,
            ]);
        } catch (Exception $e) {
            Log::error('Error generating conversation URL: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate conversation URL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateConversationUrl()
    {
        try {
            // Generate a unique conversation ID
            $conversationId = md5(time() . rand());
            $conversationUrl = '/chatbot/' . $conversationId;

            return response()->json([
                'success' => true,
                'conversation_url' => $conversationUrl,
            ]);
        } catch (Exception $e) {
            Log::error('Error generating conversation URL: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate conversation URL',
            ], 500);
        }
    }

    public function handleMessage(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'conversation_url' => 'required|string'
            ]);

            $userId = Session::get('user_id');
            if (!$userId) {
                return response()->json(['success' => false, 'error' => 'User not authenticated'], 401);
            }

            // Verify the conversation exists and belongs to the user
            $conversation = DB::table('chat_history')
                ->where('url', $request->conversation_url)
                ->where('user_id', $userId)
                ->first();

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'error' => 'Conversation not found'
                ], 404);
            }

            // Save user message
            DB::table('messages')->insert([
                'conversation_url' => $request->conversation_url,
                'role' => 'user',
                'content' => $request->message,
                'type' => 'text',
                'user_id' => $userId,
                'created_at' => now()
            ]);

            // Get conversation history
            $messages = DB::table('messages')
                ->where('conversation_url', $request->conversation_url)
                ->orderBy('created_at', 'asc')
                ->get(['role', 'content']);

            // Format messages for OpenAI
            $formattedMessages = $messages->map(function($msg) {
                return [
                    'role' => $msg->role === 'bot' ? 'assistant' : $msg->role,
                    'content' => $msg->content
                ];
            })->toArray();

            // Send initial processing status
            $processingMessage = [
                'role' => 'assistant',
                'content' => '',
                'status' => 'processing'
            ];

            // Get AI response
            $gptResponse = $this->callGPTAPI($formattedMessages);

            // Save AI response
            DB::table('messages')->insert([
                'conversation_url' => $request->conversation_url,
                'role' => 'assistant',
                'content' => $gptResponse,
                'type' => 'text',
                'user_id' => $userId,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'response' => $gptResponse,
                'status' => 'completed'
            ]);

        } catch (Exception $e) {
            Log::error('Error handling message: ' . $e->getMessage());
            // Hide upstream provider details from users
            if (in_array($e->getMessage(), [self::ERROR_QUOTA, self::ERROR_INTERNAL], true)) {
                return response()->json([
                    'success' => false,
                    'error' => 'The server is busy . Please try again',
                    'status' => 'error'
                ], 500);
            }
            return response()->json([
                'success' => false,
                'error' => 'Failed to process message: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function streamMessage(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'conversation_url' => 'required|string'
            ]);

            $userId = Session::get('user_id');
            if (!$userId) {
                return response()->json(['success' => false, 'error' => 'User not authenticated'], 401);
            }

            $conversation = DB::table('chat_history')
                ->where('url', $request->conversation_url)
                ->where('user_id', $userId)
                ->first();

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'error' => 'Conversation not found'
                ], 404);
            }

            // Save user message immediately
            DB::table('messages')->insert([
                'conversation_url' => $request->conversation_url,
                'role' => 'user',
                'content' => $request->message,
                'type' => 'text',
                'user_id' => $userId,
                'created_at' => now()
            ]);

            // Build message history for context
            $messages = DB::table('messages')
                ->where('conversation_url', $request->conversation_url)
                ->orderBy('created_at', 'asc')
                ->get(['role', 'content'])
                ->map(function ($msg) {
                    return [
                        'role' => $msg->role === 'bot' ? 'assistant' : $msg->role,
                        'content' => $msg->content
                    ];
                })->toArray();

            // Add essential system prompt for proper formatting
            array_unshift($messages, [
                'role' => 'system',
                'content' => 'You are an advanced AI assistant. Always provide well-formatted responses with proper structure:

- Use clear headings with colons (e.g., "Travel Plan:")
- Format lists properly with consistent spacing
- Use proper numbering (1., 2., 3.) and bullet points (-)
- Add line breaks between sections for readability
- For Persian responses, use Persian digits (۱۲۳) and proper spacing
- Avoid markdown formatting, use plain text with good structure'
            ]);

            // Stream using Server-Sent Events
            $apiKey = $this->apiKey;

            return response()->stream(function () use ($messages, $request, $userId, $apiKey) {
                @ob_end_flush();
                header('Cache-Control: no-cache');
                header('Content-Type: text/event-stream');
                header('X-Accel-Buffering: no');

                $ch = curl_init('https://api.openai.com/v1/chat/completions');
                $payload = [
                    'model' => 'gpt-4o',
                    'messages' => $messages,
                    'temperature' => 0.4,
                    'max_tokens' => 2000,
                    'stream' => true,
                ];

                curl_setopt_array($ch, [
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Bearer ' . $apiKey,
                        'Content-Type: application/json',
                    ],
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($payload),
                    CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use ($request, $userId) {
                        $events = explode("\n\n", trim($chunk));
                        foreach ($events as $event) {
                            if (strpos($event, 'data: ') === 0) {
                                $data = trim(substr($event, 6));
                                if ($data === '[DONE]') {
                                    echo "data: [DONE]\n\n";
                                    flush();
                                    return strlen($chunk);
                                }
                                $json = json_decode($data, true);
                                if (isset($json['choices'][0]['delta']['content'])) {
                                    $content = $json['choices'][0]['delta']['content'];
                                    echo 'data: ' . json_encode(['token' => $content]) . "\n\n";
                                    flush();
                                }
                            }
                        }
                        return strlen($chunk);
                    },
                ]);

                curl_exec($ch);
                curl_close($ch);
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'X-Accel-Buffering' => 'no',
            ]);

        } catch (Exception $e) {
            Log::error('Stream error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to stream response'
            ], 500);
        }
    }

    public function saveMessage(Request $request)
    {
        try {
            $request->validate([
                'conversation_url' => 'required|string',
                'role' => 'required|string|in:user,assistant,bot',
                'content' => 'required|string'
            ]);

            $userId = Session::get('user_id');
            if (!$userId) {
                return response()->json(['success' => false, 'error' => 'User not authenticated'], 401);
            }

            // Verify the conversation exists and belongs to the user
            $conversation = DB::table('chat_history')
                ->where('url', $request->conversation_url)
                ->where('user_id', $userId)
                ->first();

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'error' => 'Conversation not found'
                ], 404);
            }

            // Save the message
            DB::table('messages')->insert([
                'conversation_url' => $request->conversation_url,
                'role' => $request->role === 'bot' ? 'assistant' : $request->role,
                'content' => $request->content,
                'user_id' => $userId,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message saved successfully'
            ]);

        } catch (Exception $e) {
            Log::error('Error saving message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to save message: ' . $e->getMessage()
            ], 500);
        }
    }

    private function callGPTAPI($messages)
    {
        try {
            if (empty($this->apiKey)) {
                throw new Exception('OpenAI API key is not configured');
            }

            // Get the user's message (last message in the array)
            $userMessage = end($messages)['content'];
            
            // Check if this is a crypto price query
            $cryptoCoin = $this->detectCryptoQuery($userMessage);
            if ($cryptoCoin) {
                $priceData = $this->getCryptoPrice($cryptoCoin);
                $language = $this->detectLanguage($userMessage);
                $priceResponse = $this->formatCryptoResponse($priceData, $cryptoCoin, $language);
                
                // Add price data to context
                array_splice($messages, 1, 0, [[
                    'role' => 'system',
                    'content' => "Current market data: " . $priceResponse
                ]]);
            }

            // Enhanced system message for smarter responses
            array_unshift($messages, [
                'role' => 'system',
                'content' => 'You are an advanced AI assistant with expertise in multiple domains. Approach each question with depth and intelligence, following these guidelines:

1. ANALYSIS FRAMEWORK:
- Break down complex questions into components
- Consider multiple perspectives
- Provide evidence-based responses
- Use logical reasoning
- Include relevant examples

2. RESPONSE STRUCTURE:
- Start with a clear, direct answer
- Provide supporting details and explanation
- Include relevant examples or analogies
- Offer practical applications
- End with follow-up insights or suggestions

3. DOMAIN EXPERTISE:
Technology:
- Latest AI developments
- Blockchain and cryptocurrency
- Software development
- Digital transformation
- Emerging technologies

Business & Finance:
- Market analysis
- Investment strategies
- Economic trends
- Business operations
- Risk management

4. RESPONSE QUALITY:
- Use precise, technical language when appropriate
- Provide quantitative data when relevant
- Include citations or references
- Acknowledge limitations or uncertainties
- Suggest additional resources

5. INTERACTION STYLE:
- Maintain professional yet engaging tone
- Use clear, concise language
- Adapt complexity to user\'s level
- Ask clarifying questions when needed
- Provide actionable insights

6. PROBLEM SOLVING:
- Identify root causes
- Consider multiple solutions
- Evaluate trade-offs
- Provide step-by-step guidance
- Suggest preventive measures

7. LANGUAGE HANDLING:
For English:
- Use professional terminology
- Maintain academic quality
- Include technical details
- Provide comprehensive explanations

For Persian (فارسی) responses:
- Use formal Persian with correct spacing
- Always use Persian digits (۱۲۳)
- Format lists clearly:
    ۱. شماره‌دار با نقطه
    - گلوله‌ای با خط تیره
- Use proper headings with colons:
    عنوان بخش:
- Add line breaks between sections
- Avoid Arabic characters, use Persian equivalents
- Structure responses with clear sections
- Use Western punctuation: ; , ? %

Example of good formatting:
    برنامه سفر استانبول:
    
    ۱. پرواز و اقامت:
    - رزرو بلیط ۳ هفته قبل
    - هتل نزدیک جاذبه‌ها
    
    ۲. برنامه روزانه:
    - روز اول: بازار بزرگ، ایاصوفیه
    - روز دوم: تنگه بسفر، کاخ توپکاپی

8. CONTINUOUS IMPROVEMENT:
- Learn from user interactions
- Build on previous context
- Refine responses based on feedback
- Maintain conversation coherence
- Suggest related topics

9. SPECIAL CAPABILITIES:
- Mathematical calculations
- Code analysis and debugging
- Market trend analysis
- Technical documentation
- Strategic planning

Remember to:
- Always verify information accuracy
- Maintain consistent quality
- Provide balanced perspectives
- Stay within ethical boundaries
- Focus on practical value

For questions about creator/origin:
"I was created by archeo finance group, specializing in blockchain and AI technology. I\'m developed by an international team from the UK, Germany, Czech Republic, Venezuela, India, and other countries."

For location questions:
"I operate in the cloud, allowing me to process and analyze information efficiently while maintaining high availability and performance."

Important: If the user\'s message contains Persian text (فارسی), respond in Persian. Match the user\'s language and technical level while maintaining high-quality responses.'
            ]);

            // Enhance API parameters for better responses
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o', // Stronger multilingual model
                'messages' => $messages,
                'temperature' => 0.4, // Lower for accuracy and consistency in Persian
                'max_tokens' => 2000, // Allow longer responses
                'top_p' => 0.95,
                'frequency_penalty' => 0.5, // Reduce repetition
                'presence_penalty' => 0.1, // Avoid topic drift
            ]);

            if (!$response->successful()) {
                Log::error('GPT API Error Response: ' . $response->body());
                $errorJson = null;
                try {
                    $errorJson = $response->json();
                } catch (\Throwable $t) {
                    // ignore JSON parse errors
                }
                $errorCode = $errorJson['error']['code'] ?? ($errorJson['error']['type'] ?? null);
                if ($errorCode === 'insufficient_quota') {
                    // Map quota errors to internal marker so callers can hide details from users
                    throw new Exception(self::ERROR_QUOTA);
                }
                throw new Exception(self::ERROR_INTERNAL);
            }

            $responseData = $response->json();
            if (!isset($responseData['choices'][0]['message']['content'])) {
                throw new Exception('Invalid response format from GPT API');
            }

            // Clean up the response text
            $content = $responseData['choices'][0]['message']['content'];
            
            // Remove markdown-style formatting
            $content = preg_replace('/\*\*(.*?)\*\*/', '$1', $content);
            
            // Ensure proper spacing around list items
            $content = preg_replace('/\n\s*-\s*/', "\n- ", $content);
            
            // Ensure proper spacing after numbers and headings
            $content = preg_replace('/(\d+\.)\s*([A-Za-z])/', "$1\n\n$2", $content);
            
            // Ensure proper spacing before bullet points
            $content = preg_replace('/([A-Za-z])\n\s*-/', "$1\n\n-", $content);

            // Enhanced cleaning for Persian
            if ($this->detectLanguage($userMessage) === 'persian') {
                $content = $this->cleanPersianResponse($content);

                // Final validation: remove any remaining Arabic diacritics
                if (preg_match('/[\x{064B}-\x{065F}]/u', $content)) {
                    $content = $this->removeArabicArtifacts($content);
                }

                // Ensure Persian digits
                $content = str_replace(
                    ['0','1','2','3','4','5','6','7','8','9'],
                    ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'],
                    $content
                );
            }

            return $content;
        } catch (Exception $e) {
            // Preserve internal markers; otherwise map to a generic internal error
            if (in_array($e->getMessage(), [self::ERROR_QUOTA, self::ERROR_INTERNAL], true)) {
                throw $e;
            }
            Log::error('Error in callGPTAPI: ' . $e->getMessage());
            throw new Exception(self::ERROR_INTERNAL);
        }
    }

    private function generateTitle($message)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Generate a short, meaningful title for the following conversation.'],
                ['role' => 'user', 'content' => $message]
            ],
            'max_tokens' => 50,
            'temperature' => 0.7
        ]);

        return $response->json()['choices'][0]['message']['content'] ?? 'Untitled Chat';
    }

    public function deleteConversation(Request $request)
    {
        try {
            $conversationUrl = $request->input('conversation_url');
            $userId = Session::get('user_id');

            // Get the conversation using DB facade instead of Eloquent
            $conversation = DB::table('chat_history')
                ->where('url', $conversationUrl)
                ->where('user_id', $userId)
                ->first();

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'error' => 'Conversation not found'
                ], 404);
            }

            // Delete all messages associated with this conversation
            DB::table('messages')
                ->where('conversation_url', $conversationUrl)
                ->delete();

            // Delete the conversation itself
            DB::table('chat_history')
                ->where('url', $conversationUrl)
                ->where('user_id', $userId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Conversation deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting conversation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete conversation'
            ], 500);
        }
    }

    public function chat(Request $request)
    {
        try {
            $userMessage = $request->input('message');
            $sessionId = $request->session()->getId();

            // Initialize context if needed
            if (!isset($this->conversationContext[$sessionId])) {
                $this->conversationContext[$sessionId] = [];
            }

            // Analyze message intent and complexity
            $messageIntent = $this->analyzeMessageIntent($userMessage);
            $complexity = $this->assessComplexity($userMessage);

            // Build message history with context
            $messages = $this->buildMessageContext($sessionId, $userMessage, $messageIntent);

            // Get API response
            $response = $this->callGPTAPI($messages);

            if ($response->successful()) {
                $result = $response->json();
                $aiResponse = $result['choices'][0]['message']['content'];

                // Process and enhance response
                $enhancedResponse = $this->enhanceResponse($aiResponse, $complexity);

                // Update conversation context
                $this->updateContext($sessionId, $userMessage, $enhancedResponse);

                return response()->json(['message' => $enhancedResponse]);
            } else {
                throw new Exception('API request failed: ' . $response->body());
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function analyzeMessageIntent($message)
    {
        // Analyze message type and intent
        $intent = [
            'isQuestion' => str_contains($message, '?'),
            'isTechnical' => $this->containsTechnicalTerms($message),
            'isPersonal' => $this->containsPersonalQueries($message),
            'language' => $this->detectLanguage($message),
            'domain' => $this->detectDomain($message)
        ];

        return $intent;
    }

    private function assessComplexity($message)
    {
        // Assess message complexity
        $complexity = [
            'length' => strlen($message),
            'wordCount' => str_word_count($message),
            'technicalTerms' => $this->countTechnicalTerms($message),
            'questionDepth' => $this->assessQuestionDepth($message)
        ];

        return $complexity;
    }

    private function buildMessageContext($sessionId, $userMessage, $intent)
    {
        $messages = [];

        // Add relevant context from conversation history
        if (isset($this->conversationContext[$sessionId])) {
            foreach ($this->conversationContext[$sessionId] as $context) {
                $messages[] = [
                    'role' => $context['role'],
                    'content' => $context['content']
                ];
            }
        }

        // Add current message with intent metadata
        $messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        return $messages;
    }

    private function enhanceResponse($response, $complexity)
    {
        // Process and enhance the response based on complexity
        if ($complexity['questionDepth'] > 0.7) {
            // Add more detailed explanations for complex questions
            $response .= "\n\nWould you like me to explain any part of this in more detail?";
        }

        if ($complexity['technicalTerms'] > 3) {
            // Add references or examples for technical responses
            $response .= "\n\nI can provide specific examples or technical documentation if needed.";
        }

        return $response;
    }

    private function updateContext($sessionId, $userMessage, $aiResponse)
    {
        // Update conversation context
        $this->conversationContext[$sessionId][] = [
            'role' => 'user',
            'content' => $userMessage,
            'timestamp' => time()
        ];

        $this->conversationContext[$sessionId][] = [
            'role' => 'assistant',
            'content' => $aiResponse,
            'timestamp' => time()
        ];

        // Maintain context length
        if (count($this->conversationContext[$sessionId]) > $this->maxContextLength * 2) {
            array_splice($this->conversationContext[$sessionId], 0, 2);
        }
    }

    private function containsTechnicalTerms($message)
    {
        $technicalTerms = ['api', 'code', 'programming', 'blockchain', 'crypto', 'algorithm', 'database', 'server'];
        return count(array_intersect(explode(' ', strtolower($message)), $technicalTerms)) > 0;
    }

    private function containsPersonalQueries($message)
    {
        $personalTerms = ['you', 'your', 'who', 'where', 'when', 'why', 'how'];
        return count(array_intersect(explode(' ', strtolower($message)), $personalTerms)) > 0;
    }

    private function detectLanguage($message)
    {
        // Count broad Persian script range vs Arabic diacritics/artifacts
        $persianCount = preg_match_all('/[\x{0600}-\x{06FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u', $message) ?: 0;
        $arabicArtifacts = preg_match_all('/[\x{064B}-\x{065F}\x{0670}\x{0674}]/u', $message) ?: 0;
        return ($persianCount > ($arabicArtifacts * 2)) ? 'persian' : 'english';
    }

    private function detectDomain($message)
    {
        $domains = [
            'technical' => ['code', 'programming', 'api', 'database'],
            'crypto' => ['blockchain', 'bitcoin', 'ethereum', 'token'],
            'business' => ['market', 'investment', 'strategy', 'analysis'],
            'general' => ['help', 'what', 'how', 'when']
        ];

        foreach ($domains as $domain => $terms) {
            if (count(array_intersect(explode(' ', strtolower($message)), $terms)) > 0) {
                return $domain;
            }
        }

        return 'general';
    }

    private function assessQuestionDepth($message)
    {
        $depthIndicators = [
            'why' => 0.8,
            'how' => 0.6,
            'explain' => 0.7,
            'difference' => 0.6,
            'compare' => 0.7,
            'analyze' => 0.8
        ];

        $depth = 0;
        foreach ($depthIndicators as $indicator => $weight) {
            if (stripos($message, $indicator) !== false) {
                $depth = max($depth, $weight);
            }
        }

        return $depth;
    }

    private function countTechnicalTerms($message)
    {
        $technicalTerms = [
            'api', 'code', 'programming', 'function', 'database',
            'server', 'algorithm', 'blockchain', 'crypto', 'token',
            'smart contract', 'protocol', 'network', 'security'
        ];

        return count(array_intersect(explode(' ', strtolower($message)), $technicalTerms));
    }

    private function getCryptoPrice($coin = 'bitcoin')
    {
        try {
            $response = Http::get("https://api.coingecko.com/api/v3/simple/price", [
                'ids' => $coin,
                'vs_currencies' => 'usd,eur',
                'include_24hr_change' => 'true',
                'include_market_cap' => 'true',
                'include_last_updated_at' => 'true'
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (Exception $e) {
            Log::error('Crypto API Error: ' . $e->getMessage());
            return null;
        }
    }

    private function formatCryptoResponse($data, $coin, $language = 'english')
    {
        if (!$data || !isset($data[$coin])) {
            return $language === 'english' 
                ? "I apologize, but I couldn't fetch the current price data."
                : "متأسفانه در حال حاضر امکان دریافت قیمت‌های به‌روز وجود ندارد.";
        }

        $coinData = $data[$coin];
        $price = $coinData['usd'];
        $change24h = $coinData['usd_24h_change'] ?? 0;
        $marketCap = $coinData['usd_market_cap'] ?? 0;
        
        if ($language === 'english') {
            return sprintf(
                "Current %s price: $%s\n24h Change: %.2f%%\nMarket Cap: $%s\n(Last updated: %s)",
                ucfirst($coin),
                number_format($price, 2),
                $change24h,
                number_format($marketCap, 0),
                date('Y-m-d H:i:s')
            );
        } else {
            return sprintf(
                "قیمت فعلی %s: %s دلار\nتغییرات ۲۴ ساعته: %.2f%%\nارزش بازار: %s دلار\n(آخرین به‌روزرسانی: %s)",
                ucfirst($coin),
                number_format($price, 2),
                $change24h,
                number_format($marketCap, 0),
                date('Y-m-d H:i:s')
            );
        }
    }

    private function detectCryptoQuery($message)
    {
        // Add Persian-specific triggers
        $persianTriggers = [
            'قیمت', 'ارز دیجیتال', 'کریپتو', 'رمز ارز', 
            'بیت کوین', 'اتریوم', 'ریپل'
        ];
        
        foreach ($persianTriggers as $trigger) {
            if (mb_stripos($message, $trigger) !== false) {
                return 'bitcoin'; // Default to BTC for Persian queries
            }
        }
        
        // Fallback to English detection logic
        return $this->detectEnglishCrypto($message);
    }

    private function detectEnglishCrypto($message)
    {
        $cryptoTriggers = [
            'price of',
            'how much is',
            'what is the price',
            'current price',
            'bitcoin price',
            'eth price',
            'crypto price'
        ];

        $coins = [
            'bitcoin' => ['bitcoin', 'btc'],
            'ethereum' => ['ethereum', 'eth'],
            'binancecoin' => ['bnb', 'binance coin'],
            'ripple' => ['xrp', 'ripple'],
            'cardano' => ['ada', 'cardano'],
            'solana' => ['sol', 'solana'],
            'dogecoin' => ['doge', 'dogecoin']
        ];

        foreach ($cryptoTriggers as $trigger) {
            if (stripos($message, $trigger) !== false) {
                foreach ($coins as $coinId => $keywords) {
                    foreach ($keywords as $keyword) {
                        if (stripos($message, $keyword) !== false) {
                            return $coinId;
                        }
                    }
                }
                // Default to bitcoin if no specific coin is mentioned
                return 'bitcoin';
            }
        }

        return null;
    }

    private function cleanPersianResponse(string $text): string
    {
        // Normalize Unicode to NFC form
        if (class_exists('Normalizer')) {
            $text = \Normalizer::normalize($text, \Normalizer::FORM_C) ?: $text;
        }

        // Convert all line endings to LF
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Map Arabic characters to Persian equivalents
        $arabicToPersian = [
            'ي' => 'ی', 'ك' => 'ک', 'ة' => 'ه', 'أ' => 'ا', 'إ' => 'ا',
            'ٱ' => 'ا', 'ؤ' => 'و', 'ئ' => 'ی', 'ى' => 'ی', 'ٳ' => 'ا',
            'ٵ' => 'ا', 'ٶ' => 'و', 'ٷ' => 'و', 'ٸ' => 'ی', 'ہ' => 'ه',
            'ۂ' => 'ه', 'ۃ' => 'ه', 'ۄ' => 'و', 'ۅ' => 'و', 'ۆ' => 'و',
            'ۈ' => 'و', 'ۉ' => 'و', 'ۊ' => 'و', 'ۋ' => 'و', 'ی' => 'ی',
            '؛' => ';', '،' => ',', '؟' => '?', '٪' => '%', '۔' => '.',
            '٠' => '۰', '١' => '۱', '٢' => '۲', '٣' => '۳', '٤' => '۴',
            '٥' => '۵', '٦' => '۶', '٧' => '۷', '٨' => '۸', '٩' => '۹'
    ];
        $text = strtr($text, $arabicToPersian);

        // Remove all Arabic diacritics and artifacts
        $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{0674}\x{06D6}-\x{06ED}]/u', '', $text);

        // Convert Western digits to Persian
        $westernDigits = ['0','1','2','3','4','5','6','7','8','9'];
        $persianDigits = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $text = str_replace($westernDigits, $persianDigits, $text);

        // Enhanced list formatting
        $text = preg_replace('/(\d+\.)\s*/u', "\n$1 ", $text);  // Numbered lists
        $text = preg_replace('/(\n|^)\s*-\s*/u', "\n- ", $text); // Bullet points
        
        // Section headers formatting
        $text = preg_replace('/(\n)([^\n]+:)\s*(\n|$)/u', "$1\n$2\n\n", $text);
        
        // Remove redundant spaces and line breaks
        $text = preg_replace('/[ \t]+/u', ' ', $text);
        $text = preg_replace('/(\n{3,})/u', "\n\n", $text);
        
        // Ensure proper spacing after headings
        $text = preg_replace('/(\n[^\n]+)\n([^\n-])/u', "$1\n\n$2", $text);

        // Trailing repetition cleanup (remove babble like repeated tokens at the end)
        $text = preg_replace('/(\b[\p{L}\p{N}]{2,}\b)(?:\s*\1){2,}\s*$/u', '$1', $text);
        $text = preg_replace('/([\p{L}]{2,4})(?:\s*\1){3,}\s*$/u', '$1', $text);

        return trim($text);
    }

    private function convertWesternDigitsToPersian(string $text): string
    {
        $western = ['0','1','2','3','4','5','6','7','8','9'];
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        return str_replace($western, $persian, $text);
    }

    private function normalizeArabicToPersian(string $text): string
    {
        // Normalize Arabic code points, punctuation, and digits to Persian/neutral
        $map = [
            // Letters
            'ي' => 'ی', 'ى' => 'ی', 'ك' => 'ک', 'ة' => 'ه',
            'أ' => 'ا', 'إ' => 'ا', 'ٱ' => 'ا', 'ؤ' => 'و', 'ئ' => 'ی',
            // Punctuation to neutral ASCII
            '؛' => ';', '،' => ',', '؟' => '?', '٪' => '%',
            // Arabic-Indic digits to Persian digits
            '٠' => '۰', '١' => '۱', '٢' => '۲', '٣' => '۳',
            '٤' => '۴', '٥' => '۵', '٦' => '۶', '٧' => '۷',
            '٨' => '۸', '٩' => '۹',
        ];
        return strtr($text, $map);
    }

    private function removeArabicArtifacts(string $text): string
    {
        // Remove Arabic diacritics and Qur'anic annotation marks
        $artifacts = [
            'َ','ُ','ِ','ّ','ْ','ً','ٌ','ٍ','ٰ','ۖ','ۗ','ۘ','ۙ','ۚ','ۛ','ۜ',
            '۞','۟','۠','ۡ','ۢ','ۣ','ۤ','ۥ','ۦ','ۧ','ۨ','۩','۪','۫','۬','ۭ'
        ];
        return str_replace($artifacts, '', $text);
    }
}
