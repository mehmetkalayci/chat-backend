<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    protected $fillable = [
        'botId',
        'botName',
        'description',
        'labels',
        'color',
        'showButtonLabel',
        'alignment',
        'horizontalMargin',
        'verticalMargin',
        'loginUrl',
        'prompt',
        'openai_api_key',
    ];

    protected $casts = [
        'chatbot_id' => 'string',
        'labels' => 'json', // 'labels' sütunu bir JSON olarak saklanacak
        'showButtonLabel' => 'boolean', // 'showButtonLabel' sütunu bir boolean olarak saklanacak
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
