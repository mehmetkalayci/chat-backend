<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Chatbot;
use App\Models\ChatbotLog;
use App\Models\ChatbotQuizResponse;
use App\Models\ChatbotUser;
use App\Models\ChatbotQuestion;
use App\Models\ChatbotQuestionEvaluation;
use App\Models\ChatbotQuestionResponse;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ChatbotController extends Controller
{
    /**
     * https://api.terapivitrini.com/api/auth/member/control aracılığıyla gelen token değerini kontrol ediyoruz
     */
    private function validateTokenAndExtractData($token)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
        ])
            ->timeout(60)
            ->post('https://api.terapivitrini.com/api/auth/member/control');

        if ($response->successful()) {
            $data = $response->json();

            // İstenilen değerler
            $userId = $data['id'];
        } else {
            return [
                'type' => 'error',
                'message' => "Oturumunuzun süresi doldu ya da geçersiz oturum. Lütfen tekrar giriş yapın. <a href='https://terapivitrini.com/login'> Giriş yapmak için tıklayın. </a>"
            ];
        }

        return ['user_id' => $userId];
    }

    /**
     * ChatGPT'ye soru sormak için kullan
     */
    private function evaluateQuizViaChatGPT($systemPrompt, $userPrompt)
    {
        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json'
                ])
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    "model" => "gpt-3.5-turbo",
                    "temperature" => 1,
                    "messages" => [
                        [
                            "role" => "system",
                            "content" => $systemPrompt
                        ],
                        [
                            "role" => "user",
                            "content" => $userPrompt
                        ]
                    ],
                    //"max_tokens" => 1000
                ]);

            return $response->json(); //['choices'][0]['message']['content'];
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * ChatGPT'ye soru sormak için kullan
     */
    private function questionClassification($userPrompt)
    {
        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json'
                ])
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    "model" => "gpt-3.5-turbo",
                    "temperature" => 0.5,
                    "messages" => [
                        [
                            "role" => "system",
                            "content" => "Uzman bir bilişsel davranışçı terapist olarak davran. Olası durumları düşünün ve kullanıcının mesajı psikolojik danışmanlık kapsamında mı kontrol edin? Eğer öyleyse 1 değilse 0 değerini döndür."
                        ],
                        [
                            "role" => "user",
                            "content" => $userPrompt
                        ]
                    ],
                    //"max_tokens" => 1000
                ]);

            return $response->json(); //['choices'][0]['message']['content'];
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * ChatGPT'ye geçmiş ile beraber soru sormak için kullan
     */
    private function askChatGPTwithHistory($systemPrompt, $question, $userId, $historyMessageLimit = 10)
    {
        try {
            $messages = [];

            // Sistem mesajını ekle
            $messages[] = [
                "role" => "system",
                "content" => $systemPrompt
            ];

            // Kullanıcının mesajlarını al, son 5 mesajı al
            $history = ChatbotLog::where('chatbot_user_id', $userId)->orderBy('log_id', 'asc')->limit($historyMessageLimit)->get();

            // Mesajları veri yapısına ekleyin
            foreach ($history as $index => $message) {
                $messages[] = [
                    'role' => $message->variant == 'chatbot' ? 'assistant' : 'user',
                    'content' => $message->message
                ];
            }

            // Kullanıcının mesajını ekle
            $messages[] = [
                "role" => "user",
                "content" => $question
            ];

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json'
                ])
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    "model" => "gpt-3.5-turbo",
                    "temperature" => 1,
                    "messages" => $messages,
                    //"max_tokens" => 1000
                ]);

            // Yapay zeka cevaplarını kullanıcı mesajlarına yerleştirin
            return $response->json(); //['choices'][0]['message']['content'];
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Chatbot ayarlarını çekmek için kullan
     */
    public function getSettings($chatbotId)
    {
        // Verilen UUID ile eşleşen bir chatbotu çekmek için
        $chatbot = Chatbot::where('chatbot_id', $chatbotId)->first();

        if (!$chatbot) {
            return response()->json([
                'type' => 'error',
                'message' => "Chatbot ($chatbotId) bulunamadı."
            ], 404);
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

    // Token ile gelen kullanıcı daha önceden bu chatbota ait soruları yanıtlamış mı?
    public function haveChatbotQuestionsReplied(Request $request)
    {
        $token = $request->token;
        $chatbotId = $request->chatbotId;

        $result = $this->validateTokenAndExtractData($token);

        if (isset($result['type']) && $result['type'] === 'error') {
            // Token geçerli değil veya bir hata oluştu
            return response()->json($result, 401);
        } else {
            // Token doğrulandı ve veriler alındı
            $userId = $result['user_id'];

            // Kullanıcı daha önce bu chatbota ait soruları yanıtlamış mı kontrol et
            $hasReplied = ChatbotQuestionEvaluation::where('chatbot_id', $chatbotId)->where('chatbot_user_id', $userId)->exists();

            $questions = [];
            if (!$hasReplied) {
                // Kullanıcı bu chatbota ait soruları daha önce yanıtlamamış, soruları getir
                $questions = ChatbotQuestion::where('chatbot_id', $chatbotId)->get();
            }

            $responseData = [
                'chatbotId' => $chatbotId,
                'userId' => $userId,
                'chatbotQuestions' => $questions,
            ];

            // Kullanıcı daha önce soruları yanıtlamış
            return response()->json($responseData);
        }
    }

    public function loadHistory(Request $request)
    {
        // Token'ı alın
        $token = $request->token;

        $result = $this->validateTokenAndExtractData($token);

        if (isset($result['type']) && $result['type'] === 'error') {
            // Token geçerli değil veya bir hata oluştu
            return response()->json($result, 401);
        } else {
            // Token doğrulandı ve veriler alındı
            $userId = $result['user_id'];

            // Kullanıcının mesajlarını al
            $messages = ChatbotLog::where('chatbot_user_id', $userId)->orderBy('created_at', 'asc')->get();

            // Geriye dönecek veri yapısını oluşturun
            $responseData = [
                'userId' => $userId,
                //'messageLimit' => 10,
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

    public function deleteHistory(Request $request)
    {
        $token = $request->token;

        $result = $this->validateTokenAndExtractData($token);

        if (isset($result['type']) && $result['type'] === 'error') {
            // Token geçerli değil veya bir hata oluştu
            return response()->json($result, 401);
        } else {
            // Token doğrulandı ve veriler alındı
            $userId = $result['user_id'];

            // Kullanıcıya ait tüm ChatbotLog kayıtlarını sil
            ChatbotLog::where('chatbot_user_id', $userId)->delete();

            return response()->json(['message' => 'Sohbet geçmişi silindi.']);
        }
    }

    public function makePayment(Request $request)
    {
        // TODO: Ödeme işlemleri yapılacak
        return null;
    }

    public function evaluateTest(Request $request)
    {
        // request içerisindeki token, chatbotId, questions ve answers değerlerini alın
        $token = $request->token;
        $chatbotId = $request->chatbotId;
        $questionsAndAnswers = $request->questionsAndAnswers;

        // Token'ı doğrulayın ve kullanıcı verilerini alın
        $result = $this->validateTokenAndExtractData($token);

        // Kullanıcı verilerini alın
        if (isset($result['type']) && $result['type'] === 'error') {
            // Token geçerli değil veya bir hata oluştu
            return response()->json($result, 401);
        } else {
            // Token doğrulandı ve veriler alındı
            $userId = $result['user_id'];

            $chatbot = Chatbot::where('chatbot_id', $chatbotId)->first(); // İstek yapmak istediği chatbot hakikaten var mı?
            if (!$chatbot) {
                return response()->json([
                    "type" => "error",
                    "message" => "Bu chatbot ($chatbotId) sistemden kaldırılmış."
                ], 404);
            }

            $questionsAndAnswersString = collect($questionsAndAnswers)->map(function ($item, $key) {
                if (isset($item['value'], $item['answer'])) {
                    return "Soru " . ($key + 1) . ": " . $item['value'] . "\nCevap " . ($key + 1) . ": " . $item['answer'];
                }
            })->implode("\n\n");


            // Chatbot nesnesi içinden soruları değerlendirmek için kullanılacak olan quiz_evaluation_prompt değerini alın
            $quizEvaluationPrompt = $chatbot->quiz_evaluation_prompt;

            // Quiz promptunu kullanarak kullanıcının cevaplarını chatgpt ile değerlendirin
            $evaluationOfTest = $this->evaluateQuizViaChatGPT($quizEvaluationPrompt, $questionsAndAnswersString);

            // Değerlendirme sonucunu kontrol et ve kayıt işlemlerini yap eğer hata varsa hata mesajını döndür
            if (!isset($evaluationOfTest['choices'][0]['message']['content'])) {
                return response()->json([
                    'type' => 'error',
                    'message' => "($chatbot->name) ile değerlendirme yapılırken bir hata oluştu."
                ], 500);
            }

            // Kayıt işlemleri başlasın
            DB::beginTransaction();

            try {
                // Kullanıcıyı kaydet veya varsa geç
                $user = ChatbotUser::firstOrCreate(['chatbot_user_id' => $userId]);

                // Kullanıcının chatbot sorularını ait cevaplarının yapay zeka tarafından değerlendirmesini kaydedin
                ChatbotQuestionEvaluation::updateOrInsert(
                    [
                        'chatbot_id' => $chatbotId,
                        'chatbot_user_id' => $userId,
                        'evaluation' => $evaluationOfTest['choices'][0]['message']['content'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Kullanıcının chatbot sorularına ait cevaplarını ChatbotQuestionResponse kaydedin
                foreach ($questionsAndAnswers as $key => $item) {
                    ChatbotQuestionResponse::updateOrInsert(
                        [
                            'chatbot_id' => $chatbotId,
                            'chatbot_user_id' => $userId,
                            'question' => $item['value'],
                            'answer' => $item['answer'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // Kullanıcının chatbot sorularına ait cevaplarını ChatbotLog kaydedin
                foreach ($questionsAndAnswers as $key => $item) {
                    ChatbotLog::create([
                        'chatbot_id' => $chatbotId,
                        'chatbot_user_id' => $userId,
                        'variant' => 'chatbot',
                        'message' => $item['value'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    ChatbotLog::create([
                        'chatbot_id' => $chatbotId,
                        'chatbot_user_id' => $userId,
                        'variant' => 'user',
                        'message' => $item['answer'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                ChatbotLog::create([
                    'chatbot_id' => $chatbotId,
                    'chatbot_user_id' => $userId,
                    'variant' => 'chatbot',
                    'message' => $evaluationOfTest['choices'][0]['message']['content'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // İşlem başarılı, commit yap
                DB::commit();

                // Chatbot cevabını döndür
                return response()->json(['message' => $evaluationOfTest['choices'][0]['message']['content']]);
            } catch (\Throwable $th) {
                // İşlem başarısız oldu, geri al
                DB::rollback();

                return response()->json([
                    'type' => 'error',
                    'message' => 'İşlem sırasında bir hata oluştu: ' . $th->getMessage(),
                ], 500);
            }
        }
    }

    public function askQuestion(Request $request)
    {
        $token = $request->token;
        $chatbotId = $request->chatbotId;
        $question = $request->question;
        $result = $this->validateTokenAndExtractData($token);

        if (isset($result['type']) && $result['type'] === 'error') {
            // Token geçerli değil veya bir hata oluştu
            return response()->json($result, 401);
        } else {
            // Token doğrulandı ve veriler alındı
            $userId = $result['user_id'];

            // chatbotId değeri ile eşleşen bir chatbot var mı?
            $chatbot = Chatbot::where('chatbot_id', $chatbotId)->first();
            if (!$chatbot) {
                return response()->json([
                    'type' => 'error',
                    'message' => "Chatbot ($chatbotId) bulunamadı."
                ], 404);
            }

            // $chatbot nesnesine ait chatbot_prompt değerini alın
            $chatbotPrompt = $chatbot->chatbot_prompt;

            // try catch ile transaction başlat
            try {
                DB::beginTransaction();

                // ChatGPT'ye soruyu sor
                $response = $this->evaluateQuizViaChatGPT($chatbotPrompt, $question);

                //$response = $this->askChatGPTwithHistory($chatbotPrompt, $question, $userId);

                // ChatGPT'den gelen cevabı kaydet
                ChatbotLog::create([
                    'chatbot_id' => $chatbotId,
                    'chatbot_user_id' => $userId,
                    'variant' => 'chatbot',
                    'message' => $response['choices'][0]['message']['content'],
                ]);

                // Kullanıcının sorduğu soruyu kaydet
                ChatbotLog::create([
                    'chatbot_id' => $chatbotId,
                    'chatbot_user_id' => $userId,
                    'variant' => 'user',
                    'message' => $question,
                ]);

                // İşlem başarılı, commit yap
                DB::commit();

                // ChatGPT'den gelen cevabı döndür
                return response()->json(['message' => $response['choices'][0]['message']['content']]);
            } catch (\Throwable $th) {
                // İşlem başarısız oldu, geri al
                DB::rollback();

                return response()->json([
                    'type' => 'error',
                    'message' => 'İşlem sırasında bir hata oluştu: ' . $th->getMessage(),
                ], 500);
            }
        }
        return response()->json(['message' => 'Soru cevabı başarıyla kaydedildi.']);
    }

    // Gönderilen token içindeki kullanıcıya göre chatbot_logs, chatbot_question_evaluations, chatbot_question_responses tablolarındaki ilgili kullanıcıya ait tüm verileri temizle
    public function deleteEverything(Request $request)
    {
        $token = $request->token;
        $result = $this->validateTokenAndExtractData($token);

        if (isset($result['type']) && $result['type'] === 'error') {
            // Token geçerli değil veya bir hata oluştu
            return response()->json($result, 401);
        } else {
            // Token doğrulandı ve veriler alındı
            $userId = $result['user_id'];

            // try catch ile transaction başlat
            try {
                DB::beginTransaction();

                // ChatbotLog tablosundan kullanıcıya ait tüm verileri sil
                ChatbotLog::where('chatbot_user_id', $userId)->delete();

                // ChatbotQuestionEvaluation tablosundan kullanıcıya ait tüm verileri sil
                ChatbotQuestionEvaluation::where('chatbot_user_id', $userId)->delete();

                // ChatbotQuestionResponse tablosundan kullanıcıya ait tüm verileri sil
                ChatbotQuestionResponse::where('chatbot_user_id', $userId)->delete();

                // İşlem başarılı, commit yap
                DB::commit();

                return response()->json(['message' => 'Tüm veriler silindi.']);
            } catch (\Throwable $th) {
                // İşlem başarısız oldu, geri al
                DB::rollback();

                return response()->json([
                    'type' => 'error',
                    'message' => 'İşlem sırasında bir hata oluştu: ' . $th->getMessage(),
                ], 500);
            }
        }
    }
}
