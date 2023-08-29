<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotLog extends Model
{
    protected $table = 'chatbot_logs';

    protected $primaryKey = 'log_id';

    protected $fillable = ['chatbot_id', 'chatbot_user_id', 'message', 'variant', 'loading'];
    
    protected $casts = [
        'log_id' => 'string',
        'chatbot_id' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(ChatbotUser::class, 'chatbot_user_id', 'chatbot_user_id');
    }

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id', 'chatbot_id');
    }
}
