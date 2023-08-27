<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['quiz_name', 'quiz_description', 'chatbot_id'];

    protected $casts = [
        'chatbot_id' => 'string',
    ];

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id', 'chatbot_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }
}
