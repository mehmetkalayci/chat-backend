<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    use HasFactory;

    protected $primaryKey = 'chatbot_id';

    protected $casts = [
        'chatbot_id' => 'string'
    ];

    protected $fillable = [
        'chatbot_id',
        'name',
        'description',
        'input_placeholder',
        'first_message',
        'floating_button_name',
        'close_button_name',
        'chatbot_color',
        'show_button_label',
        'alignment',
        'horizontal_margin',
        'vertical_margin',
        'login_url',
    ];
}
