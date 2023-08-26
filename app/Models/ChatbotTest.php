<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'name',
        'description',
        'is_published',
        'chatbot_id',
    ];

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }
}
