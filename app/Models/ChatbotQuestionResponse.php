<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotQuestionResponse extends Model
{
    use HasFactory;

    protected $table = 'chatbot_question_responses';

    protected $primaryKey = 'chatbot_question_response_id';

    protected $fillable = [
        'chatbot_id',
        'chatbot_user_id',
        'question',
        'answer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(ChatbotUser::class, 'chatbot_user_id', 'chatbot_user_id');
    }
}
