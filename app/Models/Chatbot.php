<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    protected $fillable = ['chatbot_id', 'name', 'description', 'prompt', 'openai_api_key', 'color', 'placeholder', 'btn_name', 'show_btn', 'first_message', 'alignment', 'horizontal_margin', 'vertical_margin', 'login_url'];

    protected $casts = [
        'chatbot_id' => 'string',
        'show_btn' => 'boolean'
    ];

    protected $hidden = ['openai_api_key', 'created_at', 'updated_at'];

    public function logs()
    {
        return $this->hasMany(ChatbotLog::class, 'chatbot_id', 'chatbot_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'chatbot_id', 'chatbot_id');
    }
}
