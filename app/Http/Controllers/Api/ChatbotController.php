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


class ChatbotController extends Controller
{
    /**
     * Bu fonksiyonu önderin sistemiyle doğrulayacak şekilde değiştireceğiz
     */
    private function validateTokenAndExtractData($token)
    {
        $secretKey = env('JWT_SECRET');

        try {
            // JWT token'ı doğrulayın ve verileri çıkarın
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        } catch (\Firebase\JWT\BeforeValidException $e) {
            return ['error' => 'Token henüz geçerli değil.'];
        } catch (\Firebase\JWT\ExpiredException $e) {
            return ['error' => 'Token süresi dolmuş.'];
        } catch (\Exception $e) {
            return ['error' => 'Token geçerli değil.'];
        }

        // Token içindeki verilere erişebilirsiniz
        $userId = $decoded->sub;

        // İşlemlerinizi yapabilirsiniz, örneğin:
        // - Veritabanı sorguları
        // - Kullanıcıya ait kayıtları almak
        // - ...

        return ['user_id' => $userId]; // İstediğiniz verileri döndürün
    }

    public function getSettings($chatbotId)
    {
        // Verilen UUID ile eşleşen bir chatbotu çekmek için
        $chatbot = Chatbot::where('chatbot_id', $chatbotId)->first();

        if (!$chatbot) {
            return response()->json(['message' => 'Chatbot bulunamadı.'], 404);
        }

        return response()->json($chatbot);
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

            // Chatbot kullanıcılarını kontrol et
            $userExists = ChatbotUser::where('chatbot_user_id', $userId)->exists();

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
                    $quizQuestions = QuizQuestion::where('quiz_id', $quizId)->pluck('question')->toArray();
                } else {
                    $quizQuestions = [];
                }
            }

            // Kullanıcının mesajlarını al
            $messages = ChatbotLog::where('chatbot_user_id', $userId)
                ->orderBy('created_at', 'asc') // Sıralamayı isteğinize göre ayarlayın
                ->get();

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
