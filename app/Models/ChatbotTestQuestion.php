<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotTestQuestion extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'test_id',
        'question',
    ];

    public function chatbotTest()
    {
        return $this->belongsTo(ChatbotTest::class);
    }
}
