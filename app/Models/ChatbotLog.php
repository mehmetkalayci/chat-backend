<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotLog extends Model
{
    protected $fillable = ['log_id', 'chatbot_id', 'message', 'variant', 'loading', 'chatbot_user_id'];

    protected $casts = [
        'log_id' => 'string',
        'chatbot_id' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(ChatbotUser::class, 'chatbot_user_id');
    }

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id', 'chatbot_id');
    }
}
