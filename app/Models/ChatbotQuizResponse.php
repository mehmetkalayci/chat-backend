<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotQuizResponse extends Model
{
    protected $fillable = ['chatbot_id', 'user_id', 'quiz_id', 'question_id', 'question', 'user_answer'];

    protected $casts = [
        'chatbot_id' => 'string',
    ];

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id', 'chatbot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
