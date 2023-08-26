<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'variant',
        'message',
        'loading',
        'isLast',
        'chatbot_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(ChatbotUser::class);
    }
}
