<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotQuestion extends Model
{
    use HasFactory;

    protected $table = 'chatbot_questions';

    protected $primaryKey = 'chatbot_question_id';

    protected $fillable = [
        'chatbot_id',
        'type',
        'value',
    ];

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class, 'chatbot_id', 'chatbot_id');
    }
}
