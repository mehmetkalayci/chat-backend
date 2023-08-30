<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotQuestionEvaluation extends Model
{
    use HasFactory;

    protected $table = 'chatbot_question_evaluations';

    protected $primaryKey = 'chatbot_question_evaluation_id';

    protected $fillable = [
        'chatbot_id',
        'chatbot_user_id',
        'evaluation'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
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
