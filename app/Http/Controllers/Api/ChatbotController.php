<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Chatbot;
use App\Models\ChatbotLog;
use App\Models\ChatbotUser;
use App\Models\Quiz;
use App\Models\QuizQuestion;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Illuminate\Support\Facades\Http;


class ChatbotController extends Controller
{
    /**
     * https://api.terapivitrini.com/api/auth/member/control aracılığıyla gelen token değerini kontrol ediyoruz
     */
    private function validateTokenAndExtractData($token)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
        ])->post('https://api.terapivitrini.com/api/auth/member/control');

        if ($response->successful()) {
            $data = $response->json();

            // İstenilen değerler
            $userId = $data['id'];
        } else {
            return ['error' => 'Token geçerli değil.'];
        }

        return ['user_id' => $userId];
    }

    public function getSettings($chatbotId)
    {
        // Verilen UUID ile eşleşen bir chatbotu çekmek için
        $chatbot = Chatbot::where('chatbot_id', $chatbotId)->first();

        if (!$chatbot) {
            return response()->json(['message' => 'Chatbot bulunamadı.'], 404);
        }

        $chatbotSettings = [
            'botId' => $chatbot->chatbot_id,
            'botName' => $chatbot->name,
            'description' => $chatbot->description,
            'labels' => json_decode($chatbot->labels, true), // JSON verisini diziye çeviriyoruz
            'color' => $chatbot->color,
            'showButtonLabel' => $chatbot->show_button_label,
            'alignment' => $chatbot->alignment,
            'horizontalMargin' => $chatbot->horizontal_margin,
            'verticalMargin' => $chatbot->vertical_margin,
            'loginUrl' => $chatbot->login_url,
        ];

        return response()->json($chatbotSettings);
    }

    public function loadMessages(Request $request)
    {
        // Token'ı alın
        $token = $request->token;
        $chatbotId = $request->chatbotId;

        $result = $this->validateTokenAndExtractData($token);

        if (isset($result['error'])) {
            // Token geçerli değil veya bir hata oluştu
            return response()->json($result, 401);
        } else {
            // Token doğrulandı ve veriler alındı
            $userId = $result['user_id'];

            // Chatbot kullanıcısını kontrol et
            // Kullanıcı veritabanında olmayabilir. Ama token doğrulandığı için böyle bir kullanıcı var.
            // Kullanıcı ilk kez sistemi kullanmaya gelmiş.
            // Bu kullanıcı işlem yapabilir.
            $userExists = ChatbotUser::where('chatbot_user_id', $userId)->exists(); // Kullanıcı bizde kayıtlı mı?
            $chatbotExists = Chatbot::where('chatbot_id', $chatbotId)->exists(); // İstek yapmak istediği chatbot hakikaten var mı?

            if ($userExists && $chatbotExists) {
                dd('ok devam');
            } else {
                dd([$userExists, $chatbotExists]);
            }

            // Kullanıcı ChatbotUser tablosunda yok ise ve token valid olduğu için bu kullanıcı ilk kez gelmiştir
            // Kullanıcıya quiz sorular sorulacak, yani hasQuizTaken değeri false olacak

            // Kullanıcı quiz sorularını cevapladığı zaman kullanıcıyı ChatbotUser tablosuna ekleyeceğiz
            $hasQuizTaken = $userExists;

            $quizQuestions = [];

            if (!$hasQuizTaken) {
                // Quiz verilerini al
                $quiz = Quiz::where('chatbot_id', $chatbotId)->first();

                if ($quiz) {
                    // Quiz sorularını çek
                    $quizId = $quiz->id;
                    $quizQuestionsTemp = QuizQuestion::where('quiz_id', $quizId)->select('id', 'type', 'value')->get()->toArray();

                    // Soruları veri yapısına ekleyin
                    foreach ($quizQuestionsTemp as $index => $question) {
                        $questionData = [
                            'type' => $question['type'],
                            'value' => $question['value'],
                        ];

                        $quizQuestions[] = $questionData;
                    }
                } else {
                    $quizQuestions = [];
                }
            }

            // Kullanıcının mesajlarını al
            $messages = ChatbotLog::where('chatbot_user_id', $userId)->orderBy('created_at', 'asc')->get();

            // Geriye dönecek veri yapısını oluşturun
            $responseData = [
                'chatbotId' => $chatbotId,
                'userId' => $userId,
                'hasQuizTaken' => $hasQuizTaken,
                'quizQuestions' => $quizQuestions,
                'remainingMessages' => 10, // Kalan mesaj sayısını isteğinize göre ayarlayın
                'messages' => [],
            ];

            // Mesajları veri yapısına ekleyin
            foreach ($messages as $index => $message) {
                $messageData = [
                    'variant' => $message->variant,
                    'message' => $message->message,
                    'loading' => false,
                    'isLast' => ($index === count($messages) - 1), // Son mesajı işaretle
                ];

                $responseData['messages'][] = $messageData;
            }

            return response()->json($responseData);
        }
    }

    public function deleteMessage(Request $request)
    {
        // Örnek bir mesajı silmek için
        $messageId = $request->input('message_id');
        $message = ChatbotLog::find($messageId);
        if (!$message) {
            return response()->json(['message' => 'Mesaj bulunamadı.'], 404);
        }

        $message->delete();
        return response()->json(['message' => 'Mesaj başarıyla silindi.']);
    }

    public function makePayment(Request $request)
    {
        // Örnek bir ödeme işlemi kaydetmek için
        $data = $request->validate([
            'user_id' => 'required',
            'amount' => 'required',
            // Diğer gerekli alanlar
        ]);

        // Ödeme kaydını veritabanına ekleyin
        // Ödeme işlemini eklemek için gerekli işlemleri yapabilirsiniz.

        return response()->json(['message' => 'Ödeme başarıyla kaydedildi.']);
    }

    public function askQuestion(Request $request)
    {
        // Örnek bir soru sormak için
        $data = $request->validate([
            'user_id' => 'required',
            'quiz_id' => 'required',
            'question_id' => 'required',
            'user_answer' => 'required',
            // Diğer gerekli alanlar
        ]);

        // Kullanıcının cevabını kaydedin
        $response = new ChatbotQuizResponse($data);
        $response->save();

        // İlgili sorunun cevabını kontrol etmek ve gerekli işlemleri yapmak için daha fazla kod ekleyebilirsiniz.

        return response()->json(['message' => 'Soru cevabı başarıyla kaydedildi.']);
    }
}
