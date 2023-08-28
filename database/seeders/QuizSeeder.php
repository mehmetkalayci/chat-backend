<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Quiz;
use App\Models\Chatbot;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // İlk chatbot kaydını alalım
        $chatbot = Chatbot::first();

        // Chatbot bulunamazsa hata verelim
        if (!$chatbot) {
            throw new \Exception("Chatbot not found");
        }

        Quiz::create([
            'quiz_name' => 'Quiz Name',
            'quiz_description' => 'Quiz Description',
            'chatbot_id' => $chatbot->chatbot_id,
        ]);
    }
}
