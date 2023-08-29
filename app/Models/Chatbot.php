<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    protected $table = 'chatbots';

    protected $primaryKey = 'chatbot_id';

    protected $fillable = [
        'name',
        'description',
        'labels',
        'color',
        'show_button_label',
        'alignment',
        'horizontal_margin',
        'vertical_margin',
        'login_url',
        'chatbot_prompt',
        'quiz_evaluation_prompt',
        'openai_api_key',
    ];

    protected $casts = [
        'chatbot_id' => 'string',
        'labels' => 'json',
        'show_button_label' => 'boolean',
    ];

    protected $hidden = ['openai_api_key', 'chatbot_prompt', 'quiz_evaluation_prompt', 'created_at', 'updated_at'];

    public function questions()
    {
        return $this->hasMany(ChatbotQuestion::class, 'chatbot_id', 'chatbot_id');
    }

    public function evaluations()
    {
        return $this->hasMany(ChatbotQuestionEvaluation::class, 'chatbot_id', 'chatbot_id');
    }
}
