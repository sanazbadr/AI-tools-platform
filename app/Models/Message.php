<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    
    protected $fillable = [
        'conversation_url',
        'role',
        'content',
        'type',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_url', 'url');
    }
}
