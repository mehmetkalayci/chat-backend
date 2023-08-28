<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    protected $primaryKey = 'chatbot_id';

    protected $fillable = [
        'chatbot_id',
        'name',
        'description',
        'labels',
        'color',
        'show_button_label',
        'alignment',
        'horizontal_margin',
        'vertical_margin',
        'login_url',
        'prompt',
        'openai_api_key',
    ];

    protected $casts = [
        'chatbot_id' => 'string',
        'labels' => 'json', // 'labels' sütunu bir JSON olarak saklanacak
        'show_button_label' => 'boolean', // 'showButtonLabel' sütunu bir boolean olarak saklanacak
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
