<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(Request $request)
    {
  
        $request->validate([
            'conversation_url' => 'required|string|max:255',
            'role' => 'required|in:user,bot',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_url' => $request->conversation_url,
            'role' => $request->role,
            'content' => $request->content,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Message saved', 'data' => $message]);
    }
}
