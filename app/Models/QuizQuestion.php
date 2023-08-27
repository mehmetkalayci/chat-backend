<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = ['quiz_id', 'question'];

    protected $casts = [
        'quiz_id' => 'string',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function responses()
    {
        return $this->hasMany(ChatbotQuizResponse::class, 'question_id');
    }
}
