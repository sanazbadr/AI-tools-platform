<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'chat_history';
    
    protected $fillable = [
        'url',
        'user_id',
        'title',
        'created_at',
        'updated_at'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_url', 'url');
    }
} 