<?php

namespace Database\Seeders;

use App\Models\Chatbot;
use App\Models\ChatbotQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Quiz;
use App\Models\QuizQuestion;

class ChatbotQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $chatbot = Chatbot::first();

        if (!$chatbot) {
            throw new \Exception("Chatbot not found");
        }

        $chatbot_id = $chatbot->chatbot_id;

        ChatbotQuestion::create([
            'chatbot_id' => $chatbot_id,
            'type' => 'chatbot_message',
            'value' => 'Hoşgeldiniz, ben yapay zeka asistanınız psikolog Enver.',
        ]);

        ChatbotQuestion::create([
            'chatbot_id' => $chatbot_id,
            'type' => 'chatbot_message',
            'value' => 'Öncelikle sohbet etmeye başlamadan önce lütfen aşağıdaki test sorularını dikkatlice yanıtlayın.',
        ]);

        ChatbotQuestion::create([
            'chatbot_id' => $chatbot_id,
            'type' => 'chatbot_question',
            'value' => 'Probleminizi nasıl tanımlarsınız?',
        ]);


        ChatbotQuestion::create([
            'chatbot_id' => $chatbot_id,
            'type' => 'chatbot_question',
            'value' => 'Bu durum sizi ne kadar rahatsız ediyor? Örneğin, sosyal, iş, aile yaşantımı olumsuz etkiliyor ve beni mutsuz ediyor...',
        ]);

        ChatbotQuestion::create([
            'chatbot_id' => $chatbot_id,
            'type' => 'chatbot_question',
            'value' => 'Belirli bir durumla nasıl başa çıktığınızı açıklar mısınız?',
        ]);

        ChatbotQuestion::create([
            'chatbot_id' => $chatbot_id,
            'type' => 'chatbot_question',
            'value' => 'Bu durumda ne hissettiniz ve ne düşündünüz?',
        ]);
    }
}
