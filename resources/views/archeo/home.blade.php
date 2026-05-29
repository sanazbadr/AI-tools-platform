<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archeo.ai - AI Tools for Everyone</title>
    <meta name="description" content="Advanced AI for research, creation, and everyday tasks across all topics">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-text {
            color: #dc3545;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
        }
        
        .card-gradient {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        @keyframes fadeInUp {
            from { 
                transform: translateY(30px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { box-shadow: 0 0 20px rgba(220, 53, 69, 0.4); }
            to { box-shadow: 0 0 30px rgba(220, 53, 69, 0.7); }
        }
        
        .text-shadow {
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
                .video-overlay {
            background: linear-gradient(180deg, rgba(15,15,35,0.3) 0%, rgba(15,15,35,0.1) 50%, rgba(15,15,35,0.4) 100%);
        }
        
        /* Image Slider Styles */
        .slider-image {
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }
        
        .slider-image.active {
            opacity: 1;
        }
        
        /* Caption Overlay Styles */
        .slider-caption {
            background: rgb(123 117 117 / 35%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 10px;
            padding: 8px 16px;
        }

        /* OpenAI Style Chat Container */
        .openai-chat-container {
            animation: slideInUp 0.8s ease-out;
        }

        .chat-input-container {
            transition: all 0.3s ease;
        }

        .chat-input-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .chat-input-container:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: #3b82f6;
        }

        .sample-topic {
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        .sample-topic:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .sample-topic:active {
            transform: scale(0.95);
        }

        /* Input focus effects */
        #chatInput:focus {
            outline: none;
        }
        
        /* Flexible chat input (auto-growing textarea) */
        #chatInput {
            height: auto;
            min-height: 40px;
            max-height: 30vh;
            overflow-y: hidden; /* enable scroll dynamically via JS when capped */
            resize: none; /* user doesn't drag resize */
        }

        /* Dark scrollbar for chat input */
        #chatInput::-webkit-scrollbar {
            width: 10px;
        }
        #chatInput::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.25);
            border-radius: 8px;
        }
        #chatInput::-webkit-scrollbar-thumb {
            background: rgba(80,80,80,0.9);
            border-radius: 8px;
            border: 2px solid rgba(0,0,0,0.25);
        }
        #chatInput::-webkit-scrollbar-thumb:hover {
            background: rgba(100,100,100,1);
        }
        /* Firefox */
        #chatInput {
            scrollbar-color: #505050 rgba(0,0,0,0.25);
            scrollbar-width: thin;
        }

        /* Button hover effects */
        #chatSendBtn:hover {
            background-color: #4b5563;
        }

        @keyframes fadeInMessage {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile responsiveness for chat */
        @media (max-width: 768px) {
            .chat-intro-container {
                margin: 0 1rem;
                padding: 1rem;
            }
            
            .chat-messages {
                max-height: 200px;
            }
            
            /* Keep AI icon, input, and send button on one line */
            .chat-input-container {
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
            }
            
            .chat-input-container input {
                margin-right: 0;
                width: 100%;
            }
        }

    </style>
</head>
<body class="bg-gray-900 text-white overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-gray-900/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="/image/LogoWeb.webp" alt="Archeo.ai" class="h-8 w-auto">
                    <span class="ml-3 text-xl font-bold text-white">Archeo.ai</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-300 hover:text-white transition-colors duration-300">Home</a>
                    <a href="#about" class="text-gray-300 hover:text-white transition-colors duration-300">About</a>
                    <a href="#tools" class="text-gray-300 hover:text-white transition-colors duration-300">Services</a>
                    <a href="/login?redirect=%2Fdashboard" class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-all duration-300 animate-glow">
                        Get Started
                    </a>
                </div>
                <!-- Mobile hamburger -->
                <button id="mobileMenuBtn" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-600" aria-controls="mobileMenu" aria-expanded="false" aria-label="Open menu">
                    <svg id="hamburgerIcon" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="closeIcon" class="h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile menu panel -->
        <div id="mobileMenu" class="md:hidden hidden absolute left-0 right-0 top-16 z-50 border-t border-gray-800 bg-gray-900 shadow-lg max-h-[calc(100vh-4rem)] overflow-y-auto">
            <div class="px-4 py-4 space-y-2">
                <a href="#home" class="block rounded-md px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 transition">Home</a>
                <a href="#about" class="block rounded-md px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 transition">About</a>
                <a href="#tools" class="block rounded-md px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 transition">Services</a>
                <a href="/login?redirect=%2Fdashboard" class="block rounded-md px-3 py-2 bg-red-600 text-white text-center font-semibold hover:bg-red-700 transition">Get Started</a>
            </div>
        </div>
    </nav>
    <!-- Backdrop overlay for open mobile menu -->
    <div id="menuOverlay" class="md:hidden hidden fixed inset-0 bg-black/50 z-40"></div>

    <!-- Hero Section with Video Background -->
    <section id="home" class="relative min-h-screen flex items-center overflow-hidden">
        <!-- Video Background (Desktop Only) -->
        <div class="hidden md:block absolute inset-0 w-full h-full">
            <!-- Local Video Background -->
            <video id="headerVideo" autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover">
                <source src="/videos/cyberpunk.mp4" type="video/mp4">
                <!-- Fallback for browsers that don't support video -->
                <div class="w-full h-full bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900"></div>
            </video>
            
            <!-- Video Overlay for better text readability -->
            <div class="video-overlay absolute inset-0 bg-gradient-to-r from-gray-900/50 via-gray-900/30 to-gray-900/50"></div>
        </div>
        
        <!-- Mobile Background (No Video) -->
        <div class="md:hidden absolute inset-0 w-full h-full bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900"></div>
        
        <!-- Hero Content - Left Aligned and Smaller -->
        <div class="relative z-10 flex flex-col w-full px-4 sm:px-6 lg:px-8 pt-12 md:pt-20">
            <!-- First Row: Text and Button -->
            <div class="flex items-center">
                <div class="max-w-2xl ml-8 md:ml-16 lg:ml-24">
                    <div class="animate-float">
                        <h1 class="text-2xl md:text-4xl lg:text-6xl font-black text-white mb-4 md:mb-6 text-shadow">
                            We'll help you
                            <span class="gradient-text block">unlock the past</span>
                            like nobody's business.
                        </h1>
                    </div>
                    <!-- OpenAI Style Chat Container -->
                    <div class="openai-chat-container text-center">
                        <!-- Mobile Video Section (moved above chat input for mobile layout) -->
                        <div class="md:hidden mt-8 mb-4 w-full">
                            <div class="w-full h-64 rounded-lg overflow-hidden shadow-2xl">
                                <video id="mobileVideo" autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover">
                                    <source src="/videos/cyberpunk.mp4" type="video/mp4">
                                    <!-- Fallback for browsers that don't support video -->
                                    <div class="w-full h-full bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 flex items-center justify-center">
                                        <p class="text-gray-400 text-sm">Video loading...</p>
                                    </div>
                                </video>
                            </div>
                        </div>
                        <!-- Chat Input Container -->
                        <div class="chat-input-wrapper max-w-4xl mx-auto mb-8">
                            <div class="relative">
                                <div class="chat-input-container bg-gray-800/90 backdrop-blur-sm border border-gray-600 rounded-2xl p-3 md:p-4 flex items-center space-x-3 md:space-x-4 shadow-2xl hover:shadow-3xl transition-all duration-300">
                                    
                                    
                                    <!-- Input Field -->
                                    <textarea 
                                        id="chatInput" 
                                        placeholder="Help me improve this job description"
                                        rows="1"
                                        class="flex-1 min-w-0 bg-transparent text-white placeholder-gray-400 text-base md:text-lg focus:outline-none"
                                    ></textarea>
                                    
                                    <!-- Send Button -->
                                    <button 
                                        id="chatSendBtn" 
                                        class="flex-shrink-0 w-9 h-9 md:w-10 md:h-10 bg-gray-700 hover:bg-gray-600 text-white rounded-full flex items-center justify-center transition-all duration-300 transform hover:scale-110"
                                    >
                                        <i class="fas fa-arrow-up text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sample Topics/Buttons -->
                        <div class="sample-topics max-w-4xl mx-auto">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <button class="sample-topic bg-gray-800/50 hover:bg-gray-700/50 text-white text-sm px-4 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 border border-gray-600/50" data-question="Write a professional email">
                                    Write a professional email
                                </button>
                                <button class="sample-topic bg-gray-800/50 hover:bg-gray-700/50 text-white text-sm px-4 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 border border-gray-600/50" data-question="Explain quantum computing">
                                    Explain quantum computing
                                </button>
                                <button class="sample-topic bg-gray-800/50 hover:bg-gray-700/50 text-white text-sm px-4 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 border border-gray-600/50" data-question="Create a workout plan">
                                    Create a workout plan
                                </button>
                                <button class="sample-topic bg-gray-800/50 hover:bg-gray-700/50 text-white text-sm px-4 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 border border-gray-600/50" data-question="Write a Python function">
                                    Write a Python function
                                </button>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-blue-500/20 rounded-full blur-xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 bg-purple-500/20 rounded-full blur-xl animate-float" style="animation-delay: -3s;"></div>
    </section>

    <!-- Our AI Tools Suite Section -->
    <section id="tools" class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Our <span class="gradient-text">AI Tools Suite</span>
                </h2>
                <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                    Revolutionize your creative workflow with our cutting-edge AI technology to generate stunning AI art and captivating videos.
                </p>
            </div>
            
            <!-- Text to Image Feature -->
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16 mb-20">
                <!-- Left Side - Image Slider -->
                <div class="w-full lg:w-1/2 relative mb-8 lg:mb-0">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <div class="relative h-64 sm:h-80 md:h-96 lg:h-[500px]">
                            <img src="/image/floatingCrystal.webp" alt="AI Generated Image 1" class="slider-image active absolute inset-0 w-full h-full object-cover" data-caption="Woman on a crystal bridge in the clouds with glowing butterflies.">
                            <img src="/image/endlessDesert.webp" alt="AI Generated Image 2" class="slider-image absolute inset-0 w-full h-full object-cover" data-caption="Woman in a golden glass desert under flying light-whales.">
                        </div>
                        
                        <!-- Caption -->
                        <div class="slider-caption absolute bottom-4 left-4 right-4 bg-black/35 backdrop-blur-md rounded-2xl p-4">
                            <p class="text-white text-center font-medium text-base">Woman on a crystal bridge in the clouds with glowing butterflies.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Feature Content -->
                <div class="w-full lg:w-1/2 text-center lg:text-left">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-6">
                        Text to Image
                    </h3>
                    <p class="text-base sm:text-lg text-gray-300 mb-8 leading-relaxed">
                        Ignite your creative spark with our AI Image Generator. Describe your vision with words, and watch the powerful tool translate them into captivating artwork. Catalyze a flurry of ideas and conquer creative roadblocks.
                    </p>
                    <a href="https://ai.archeoam.com/dalle" class="inline-block bg-white text-black px-6 py-3 rounded-lg font-semibold text-base hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                        Generate Image
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- AI Videos Feature -->
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16 mb-20">
                <!-- Left Side - Video -->
                <div class="w-full lg:w-1/2 relative mb-8 lg:mb-0">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <div class="relative h-64 sm:h-80 md:h-96 lg:h-[500px]">
                            <video autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover">
                                <source src="/image/AIgenerated.mp4" type="video/mp4">
                                <!-- Fallback for browsers that don't support video -->
                                <div class="w-full h-full bg-gradient-to-br from-gray-800 via-gray-700 to-gray-800 flex items-center justify-center">
                                    <p class="text-gray-400 text-sm">Video loading...</p>
                                </div>
                            </video>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Feature Content -->
                <div class="w-full lg:w-1/2 text-center lg:text-left">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-6">
                        AI Videos
                    </h3>
                    <p class="text-base sm:text-lg text-gray-300 mb-8 leading-relaxed">
                        Transform your ideas into stunning visual narratives with our upcoming AI Video Generator. Create dynamic, engaging videos from simple text descriptions and bring your ideas to life like never before.
                    </p>
                    <div class="inline-block bg-gray-700 text-gray-300 px-6 py-3 rounded-lg font-semibold text-base cursor-not-allowed opacity-75">
                        Coming Soon
                        <i class="fas fa-clock ml-2"></i>
                    </div>
                </div>
            </div>
            
            <!-- Text to Speech Feature -->
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16 mb-20">
                <!-- Left Side - Video -->
                <div class="w-full lg:w-1/2 relative mb-8 lg:mb-0">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <div class="relative h-40 sm:h-48 md:h-56 lg:h-64">
                            <video autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover">
                                <source src="/image/AiVoice.mp4" type="video/mp4">
                                <!-- Fallback for browsers that don't support video -->
                                <div class="w-full h-full bg-gradient-to-br from-gray-800 via-gray-700 to-gray-800 flex items-center justify-center">
                                    <p class="text-gray-400 text-sm">Video loading...</p>
                                </div>
                            </video>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Feature Content -->
                <div class="w-full lg:w-1/2 text-center lg:text-left">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-6">
                        Text to Speech
                    </h3>
                    <p class="text-base sm:text-lg text-gray-300 mb-8 leading-relaxed">
                        Bring your narratives to life with our advanced AI Voice Generator. Transform written content into natural, engaging speech that makes your ideas accessible to everyone. Perfect for presentations, educational content, and interactive experiences.
                    </p>
                    <a href="https://ai.archeoam.com/tts" class="inline-block bg-white text-black px-6 py-3 rounded-lg font-semibold text-base hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                        Generate Voice
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- Voice to Text Feature -->
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16 mb-20">
                <!-- Left Side - Video -->
                <div class="w-full lg:w-1/2 relative mb-8 lg:mb-0">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <div class="relative h-40 sm:h-48 md:h-56 lg:h-64">
                            <video autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover">
                                <source src="/image/VoiceToTextGenerate.mp4" type="video/mp4">
                                <!-- Fallback for browsers that don't support video -->
                                <div class="w-full h-full bg-gradient-to-br from-gray-800 via-gray-700 to-gray-800 flex items-center justify-center">
                                    <p class="text-gray-400 text-sm">Video loading...</p>
                                </div>
                            </video>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Feature Content -->
                <div class="w-full lg:w-1/2 text-center lg:text-left">
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-6">
                        Voice to Text
                    </h3>
                    <p class="text-base sm:text-lg text-gray-300 mb-8 leading-relaxed">
                        Transform spoken words into accurate text with our advanced AI Speech Recognition. Perfect for meetings, interviews, lectures, and notes across any field. Our technology understands context and terminology across domains.
                    </p>
                    <a href="https://ai.archeoam.com/stt" class="inline-block bg-white text-black px-6 py-3 rounded-lg font-semibold text-base hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                        Start Recording
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Playbook Section -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">Turn questions into <span class="gradient-text">outcomes</span></h2>
                <p class="text-xl text-gray-400 max-w-3xl mx-auto">A creative toolkit built for everyone — four products that work together across your workflow.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Ask (Chatbot) -->
                <div class="card-gradient rounded-2xl p-6 flex flex-col justify-between hover:transform hover:scale-[1.02] transition-all duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-comments text-white"></i>
                            </div>
                            <span class="text-xs px-3 py-1 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-400/30">Reason</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Ask • Chatbot</h3>
                        <p class="text-gray-300 text-sm leading-relaxed">Draft reports, compare hypotheses, and synthesize sources with a context-aware assistant.</p>
                    </div>
                    <a href="/chatbot" class="mt-6 inline-flex items-center justify-center bg-white text-black px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-all">Open Chat <i class="fas fa-arrow-right ml-2 text-xs"></i></a>
                </div>

                <!-- Visualize (Text → Image) -->
                <div class="card-gradient rounded-2xl p-6 flex flex-col justify-between hover:transform hover:scale-[1.02] transition-all duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-image text-white"></i>
                            </div>
                            <span class="text-xs px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30">Create</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Visualize • Text → Image</h3>
                        <p class="text-gray-300 text-sm leading-relaxed">Generate reconstructions and outreach visuals by describing your scene in natural language.</p>
                    </div>
                    <a href="https://ai.archeoam.com/dalle" class="mt-6 inline-flex items-center justify-center bg-white text-black px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-all">Generate Image <i class="fas fa-arrow-right ml-2 text-xs"></i></a>
                </div>

                <!-- Narrate (Text → Speech) -->
                <div class="card-gradient rounded-2xl p-6 flex flex-col justify-between hover:transform hover:scale-[1.02] transition-all duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-pink-500 to-rose-600 flex items-center justify-center">
                                <i class="fas fa-headphones text-white"></i>
                            </div>
                            <span class="text-xs px-3 py-1 rounded-full bg-rose-500/20 text-rose-300 border border-rose-400/30">Present</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Narrate • Text → Speech</h3>
                        <p class="text-gray-300 text-sm leading-relaxed">Turn reports into compelling audio guides for exhibits, classes, or field briefings.</p>
                    </div>
                    <a href="https://ai.archeoam.com/tts" class="mt-6 inline-flex items-center justify-center bg-white text-black px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-all">Generate Voice <i class="fas fa-arrow-right ml-2 text-xs"></i></a>
                </div>

                <!-- Transcribe (Voice → Text) -->
                <div class="card-gradient rounded-2xl p-6 flex flex-col justify-between hover:transform hover:scale-[1.02] transition-all duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center">
                                <i class="fas fa-wave-square text-white"></i>
                            </div>
                            <span class="text-xs px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">Capture</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Transcribe • Voice → Text</h3>
                        <p class="text-gray-300 text-sm leading-relaxed">Record interviews and field notes and convert them into accurate, searchable text.</p>
                    </div>
                    <a href="https://ai.archeoam.com/stt" class="mt-6 inline-flex items-center justify-center bg-white text-black px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-all">Start Recording <i class="fas fa-arrow-right ml-2 text-xs"></i></a>
                </div>
            </div>

            <div class="mt-10 text-center text-gray-400 text-sm">
                <span>Works in your browser • No setup required • Built for researchers</span>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-8">
                        About <span class="gradient-text">Archeo.ai</span>
                    </h2>
                    <p class="text-xl text-gray-300 mb-6 leading-relaxed">
                        Archeo.ai is a focused AI toolkit for everyone—individuals, creators, and teams. We help you research faster, visualize ideas, and communicate clearly—right in the browser. Our suite combines a context-aware chatbot, visual generation, and speech tools to turn questions into outcomes while keeping your workflow private, simple, and effective.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-500 rounded-full mr-4"></div>
                            <span class="text-gray-300">Ask • Chatbot for drafting reports, comparing hypotheses, and synthesizing sources</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-500 rounded-full mr-4"></div>
                            <span class="text-gray-300">Visualize • Text → Image for rapid reconstructions and outreach illustrations</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-500 rounded-full mr-4"></div>
                            <span class="text-gray-300">Narrate & Transcribe • Text ↔ Speech for audio guides and accurate field notes</span>
                        </div>
                    </div>
                    <a href="#tools" class="inline-block mt-8 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
                        Explore our tools
                    </a>
                </div>
                <div class="relative">
                    <div class="card-gradient rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-white mb-4">Why teams choose Archeo.ai</h3>
                        <p class="text-gray-400 mb-6">
                            We streamline analysis and communication so you can focus on what matters. Fast, secure, and designed with real-world workflows in mind.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-gray-800/50 rounded-lg">
                                <div class="text-2xl text-blue-400"><i class="fas fa-shield-alt"></i></div>
                                <div class="text-gray-300 text-sm mt-2">Privacy-first</div>
                                <p class="text-gray-400 text-xs mt-1">Browser-based workflow with sensible defaults.</p>
                            </div>
                            <div class="text-center p-4 bg-gray-800/50 rounded-lg">
                                <div class="text-2xl text-emerald-400"><i class="fas fa-bolt"></i></div>
                                <div class="text-gray-300 text-sm mt-2">Faster research</div>
                                <p class="text-gray-400 text-xs mt-1">Drafts, summaries, and assets in minutes.</p>
                            </div>
                            <div class="text-center p-4 bg-gray-800/50 rounded-lg">
                                <div class="text-2xl text-purple-400"><i class="fas fa-university"></i></div>
                                <div class="text-gray-300 text-sm mt-2">Made for heritage</div>
                                <p class="text-gray-400 text-xs mt-1">Thoughtful UX for field, lab, and public programs.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-900/50 to-purple-900/50">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Less busywork. <span class="gradient-text">More great discoveries.</span>
            </h2>
            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                The future of intelligent work is here, and it's not just AI. It's people like you, using tools like ours. So, ready to start creating like nobody's business?
            </p>
            <a href="/login?redirect=%2Fdashboard" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-10 py-4 rounded-lg font-bold text-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 animate-glow">
                Get started
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img src="/image/LogoWeb.webp" alt="Archeo.ai" class="h-8 w-auto">
                        <span class="ml-3 text-xl font-bold text-white">Archeo.ai</span>
                    </div>
                    <p class="text-gray-400">
                        Explore our AI toolkit built for everyone: Ask with our context-aware Chatbot, Visualize with Text → Image, Narrate with Text → Speech, and Transcribe with Voice → Text — accessible, reliable, and ready for anything.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Services</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="https://ai.archeoam.com/chatbot" class="hover:text-white transition">Ask • Chatbot</a></li>
                        <li><a href="https://ai.archeoam.com/dalle" class="hover:text-white transition">Visualize • Text → Image</a></li>
                        <li><a href="https://ai.archeoam.com/tts" class="hover:text-white transition">Narrate • Text → Speech</a></li>
                        <li><a href="https://ai.archeoam.com/stt" class="hover:text-white transition">Transcribe • Voice → Text</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">About</a></li>
                        <li><a href="#" class="hover:text-white transition">Research</a></li>
                        <li><a href="#" class="hover:text-white transition">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Connect</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Archeo.ai. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Video Header System
        document.addEventListener('DOMContentLoaded', function() {
            const headerVideo = document.getElementById('headerVideo');
            const mobileVideo = document.getElementById('mobileVideo');
            
            // Function to setup video
            function setupVideo(videoElement, videoName) {
                if (videoElement) {
                    videoElement.play().catch(function(error) {
                        console.log(videoName + " autoplay failed:", error);
                    });
                    
                    // Add loading indicator
                    videoElement.addEventListener('loadstart', function() {
                        console.log(videoName + " loading started");
                    });
                    
                    videoElement.addEventListener('canplay', function() {
                        console.log(videoName + " can play");
                    });
                    
                    videoElement.addEventListener('error', function(e) {
                        console.log(videoName + " error:", e);
                    });
                }
            }
            
            // Setup both videos
            setupVideo(headerVideo, "Header Video");
            setupVideo(mobileVideo, "Mobile Video");
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to navigation
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.classList.add('bg-gray-900');
                nav.classList.remove('bg-gray-900/80');
            } else {
                nav.classList.remove('bg-gray-900');
                nav.classList.add('bg-gray-900/80');
            }
        });

        // Image Slider Functionality
        const sliderImages = document.querySelectorAll('.slider-image');
        let currentImageIndex = 0;

        let currentTypingAnimation = null;
        
        function typeWriter(element, text, speed = 50) {
            // Clear any existing animation
            if (currentTypingAnimation) {
                clearTimeout(currentTypingAnimation);
            }
            
            let i = 0;
            element.textContent = '';
            
            function type() {
                if (i < text.length) {
                    element.textContent += text.charAt(i);
                    i++;
                    currentTypingAnimation = setTimeout(type, speed);
                } else {
                    currentTypingAnimation = null;
                }
            }
            type();
        }

        function showImage(index) {
            sliderImages.forEach((img, i) => {
                img.classList.toggle('active', i === index);
            });
            
            // Update caption with typing effect
            const caption = document.querySelector('.slider-caption p');
            if (caption && sliderImages[index]) {
                const newText = sliderImages[index].getAttribute('data-caption');
                // Start new typing animation
                typeWriter(caption, newText, 60); // 60ms delay between characters
            }
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % sliderImages.length;
            showImage(currentImageIndex);
        }

        // Auto-advance slider every 5 seconds
        setInterval(nextImage, 5000);

        // Initialize typing effect for first image
        setTimeout(() => {
            showImage(0);
        }, 1000);

        // OpenAI Style Chat Functionality
        const chatInput = document.getElementById('chatInput');
        const chatSendBtn = document.getElementById('chatSendBtn');

        function handleChatSubmit() {
            const message = chatInput.value.trim();
            console.log('Home page handleChatSubmit called with message:', message);
            if (!message) return;

            // Store the message in both sessionStorage and localStorage for redundancy
            sessionStorage.setItem('pendingMessage', message);
            localStorage.setItem('pendingMessage', message);
            console.log('Stored pendingMessage in sessionStorage:', sessionStorage.getItem('pendingMessage'));
            console.log('Stored pendingMessage in localStorage:', localStorage.getItem('pendingMessage'));
            
            // Redirect to login page carrying the message via URL (works across domains)
            const encoded = encodeURIComponent(message);
            window.location.href = `https://ai.archeoam.com/chatbot?message=${encoded}&create_conversation=true`;
        }

        // Auto-resize handler for flexible textarea
        function autoSizeHomepageInput() {
            chatInput.style.height = 'auto';
            const maxH = Math.min(window.innerHeight * 0.3, 320);
            const nextH = Math.min(chatInput.scrollHeight, maxH);
            chatInput.style.height = nextH + 'px';
            chatInput.style.overflowY = (chatInput.scrollHeight > nextH) ? 'auto' : 'hidden';
        }

        // Event listeners
        chatSendBtn.addEventListener('click', handleChatSubmit);
        chatInput.addEventListener('input', autoSizeHomepageInput);
        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleChatSubmit();
            }
        });
        autoSizeHomepageInput();

        // Sample topic buttons
        document.querySelectorAll('.sample-topic').forEach(button => {
            button.addEventListener('click', () => {
                const question = button.getAttribute('data-question');
                chatInput.value = question;
                autoSizeHomepageInput();
                chatInput.focus();
            });
        });

        // Focus input on page load
        window.addEventListener('load', () => {
            chatInput.focus();
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const hamburgerIcon = document.getElementById('hamburgerIcon');
        const closeIcon = document.getElementById('closeIcon');
        function setMenu(open) {
            if (!mobileMenu) return;
            mobileMenu.classList.toggle('hidden', !open);
            document.getElementById('menuOverlay')?.classList.toggle('hidden', !open);
            hamburgerIcon.classList.toggle('hidden', open);
            closeIcon.classList.toggle('hidden', !open);
            mobileMenuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            // Lock body scroll
            document.body.style.overflow = open ? 'hidden' : '';
        }
        mobileMenuBtn?.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.contains('hidden');
            setMenu(isHidden);
        });
        // Close on escape or link click
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') setMenu(false);
        });
        mobileMenu?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setMenu(false)));
    </script>
</body>
</html> 