<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotUser extends Model
{
    protected $table = 'chatbot_users';

    protected $primaryKey = 'chatbot_user_id';

    protected $fillable = [
        'chatbot_user_id',
        'message_limit'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function logs()
    {
        return $this->hasMany(ChatbotLog::class, 'chatbot_user_id', 'chatbot_user_id');
    }
}
