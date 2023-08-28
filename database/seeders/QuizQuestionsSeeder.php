<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Quiz;
use App\Models\QuizQuestion;

class QuizQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::first();

        if (!$quiz) {
            throw new \Exception("Quiz not found");
        }

        $quizId = $quiz->id;

        QuizQuestion::create([
            'quiz_id' => $quizId,
            'type' => 'message',
            'value' => 'Merhaba.',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quizId,
            'type' => 'message',
            'value' => 'Chatbotumuzu kullanmaya başlamadan önce lütfen aşağıdaki test sorularını yanıtlayın.',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quizId,
            'type' => 'question',
            'value' => 'Probleminizi nasıl tanımlarsınız?',
        ]);
        
        QuizQuestion::create([
            'quiz_id' => $quizId,
            'type' => 'question',
            'value' => 'Bu durum sizi ne kadar rahatsız ediyor?',
        ]);
        
        QuizQuestion::create([
            'quiz_id' => $quizId,
            'type' => 'message',
            'value' => 'Örneğin: Sosyal yaşantımı olumsuz etkiyor ya da iş yaşamında stresli hissettiriyor...',
        ]);
        
        QuizQuestion::create([
            'quiz_id' => $quizId,
            'type' => 'question',
            'value' => 'Belirli bir durumla nasıl başa çıktığınızı açıklar mısınız?',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quizId,
            'type' => 'question',
            'value' => 'Bu durumda ne hissettiniz ve ne düşündünüz?',
        ]);
    }
}
