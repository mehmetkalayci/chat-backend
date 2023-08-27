<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotUser extends Model
{
    protected $fillable = ['chatbot_user_id'];

    public function logs()
    {
        return $this->hasMany(ChatbotLog::class, 'chatbot_user_id');
    }
}
