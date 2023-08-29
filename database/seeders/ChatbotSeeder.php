<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chatbot;
use Illuminate\Support\Str;

class ChatbotSeeder extends Seeder
{
    public function run()
    {
        Chatbot::create([
            'chatbot_id' => Str::uuid(),
            'name' => 'Bilişsel Davranışçı Terapi Asistanı',
            'description' => 'Bu uygulama yapay zeka destekli sanal bilişsel davranışçı terapistidir. Sizelere psikoloji alanı kapsamında kısmi yardım sağlar.',
            'labels' => json_encode([
                'inputPlaceholder' => 'Mesajınız...',
                'firstMessage' => 'Merhaba ben bilişsel davranışçı terapist. Size nasıl yardımcı olabilirim?',
                'floatingButton' => 'Sanal Asistan',
                'close' => 'Kapat',
            ]),
            'color' => '#a15a7b',
            'show_button_label' => true,
            'alignment' => 'right',
            'horizontal_margin' => 40,
            'vertical_margin' => 40,
            'login_url' => 'https://terapivitrini.com/login',
            'chatbot_prompt' => 'Chatbotunuzu buraya tanımlayabilirsiniz.',
            'quiz_evaluation_prompt' => 'Uzman Bilişsel Davranışçı Terapist olarak davranın. Aşağıda verilen soru ve yanıtlardan oluşan psikolojik değerlendirme formunda yer alan bilgileri değerlendirin ve çözüm odaklı tanı koyun.',
            'openai_api_key' => '',
        ]);
    }
}
