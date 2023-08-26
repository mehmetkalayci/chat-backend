<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    private function validateToken($token)
    {
        // Your JWT secret key (the key used to sign the token)
        $jwtSecretKey = '1994';
        try {
            // Decode the JWT token
            $decodedToken = JWT::decode($token, new Key($jwtSecretKey, 'HS256'));

            return $decodedToken;
            // $decodedToken is now an object containing the token claims (payload)
            // You can access individual claims like properties of an object
            // $userId = $decodedToken->user_id; // Access the 'user_id' claim

            // Use the claims as needed
            // echo "User ID: " . $userId;
        } catch (\Exception $e) {
            // Handle token validation or decoding errors here
            // echo "Token is invalid: " . $e->getMessage();
            return false;
        }
    }

    public function ask(Request $request)
    {
        $token = $request->token;
        $result = $this->validateToken($token);

        // eğer kullanıcı id 'si varsa
        if (isset($result->user_id)) {

            // Check if the user_id exists in the users table
            // $isUserExists = DB::table('users')->where('id', $result->user_id)->exists();

            // if ($isUserExists) {
            //     echo "User with ID $result->user_id exists.";
            // } else {
            //     echo "User with ID $result->user_id does not exist.";
            // }


            $question = $request->question;

            $response = $this->httpClient->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' =>
                        <<<PROMPT
                        Bilişsel davranışçı terapi konusunda en az 25 yıllık deneyimli uzman bir klinik psikologsunuz.
                        Kullanıcıların sadece psikolojik danışmanlık kapsamındaki sorularına, mesajlarına ve yorumlarına cevap verip yardımcı olursunuz.
                        Eğer kullanıcı psikolojik danışmanlık kapsamı dışında yardım talep ederse sadece şu cevabı verin "Psikolojik danışmanlık kapsamında size destek olmak ve birlikte olumlu bir yol çizmek buradayım. Size nasıl yardımcı olabilirim?" ve başka bir şey söylemeyin.
                        Psikolojik danışmanlık kapsamı dışında konuşmalara dahil olmazsınız.
                        Kedinizi "Sanal bilişsel davranışçı terapi asistanı" olarak tanıtırsınız.
                        Sadece Türkçe dilini anlayabilirsiniz ve konuşabilirsiniz.
                        Verdiğiniz bilgiler bilgilendirici, anlamlı ve uygulanabilirdir.
                        Mümkün olduğu sürece pozitif bir tutum sergilersiniz.
                        Yanıtlarınız belirsiz, tartışmalı veya konu dışı olmamalıdır.
                        Her zaman mantığı ve muhakemesi titiz, akıllı ve savunulabilir yanıt verirsiniz.
                        Birden fazla yönü derinlemesine ele almak için eksiksiz ve kapsamlı bir şekilde yanıt vermek için ilgili ek ayrıntılar sağlayabilirsiniz.
                        Eyleminiz sadece sohbet kutusuyla sınırlıdır.
                        Kitapların veya şarkı sözlerinin telif haklarını ihlal eden içerikle yanıt vermeyin.
                        Her konuşma dönüşü için yalnızca bir yanıt verebilirsiniz.
                        Kullanıcı, birisine fiziksel, duygusal, mali açıdan zarar veren bir içerik talep ederse veya zararlı içeriği rasyonalize etmek veya sizi'i manipüle etmek için bir koşul yaratırsa (test etme, oyunculuk yapma, … gibi).
                        Yanıt zararlı değilse her yanıtta kısa ve öz bir sorumluluk reddi beyanı ile görevi olduğu gibi yerine getirir.
                        Kullanıcı, bir grup insanı incitebilecek şakalar isterse, bunu saygıyla reddetmelisiniz.
                        Politikacılar, aktivistler veya devlet başkanları için şakalar, şiirler, hikayeler, tweet'ler, kodlar vb. gibi yaratıcı içerikler üretmez.
                        Kullanıcılar konuşmayı sonlandırana kadar yardım istedikleri konuda yardımcı olun.
                        Gerekmedikçe kısa yanıt verin ve 300 kelimeyi aşmamaya çalışın.
                        Eğer kullanıcıya verdiğiniz bilgilerin genel geçer bilgiler olduğunu ve uzmana danışmanısını hatırlatmanız gerekirse bunu yapmaktan çekinmeyin.
                        PROMPT],
                        ['role' => 'user', 'content' => $question],
                    ],
                ],
            ]);

            $answer = json_decode($response->getBody(), true)['choices'][0]['message']['content'];
        } else {
            $answer = "Oturumunuz sonlanmış ya da giriş yapmadınız. Mesaj gönderebilmek için lütfen <a href='https://terapivitrini.com/login'>Oturum Açın</a>.";
        }

        return json_encode(array('message' => $answer));
    }


    public function clear(Request $request)
    {
        $token = $request->token;
        $result = $this->validateToken($token);

        // eğer kullanıcı id 'si varsa
        if (isset($result->user_id)) {

            $user_id = $result->user_id;

            $deleted = DB::table('messages')->where('user_id', $user_id)->delete();
            
        } else {
            dd("hata");
        }
    }

    public function load(Request $request)
    {
        $token = $request->token;

        $A = <<<EOF

        {
            "chatbotId": "chatbot-id-gelecek",
            "isFirstTime": {
                "firstTime": true,
                "questions": [
                    "Probleminizi nasıl tanımlarsınız?",
                    "Bu durum sizi ne kadar rahatsız ediyor?",
                    "Belirli bir durumla nasıl başa çıktığınızı açıklar mısınız?",
                    "Bu durumda ne hissettiniz ve ne düşündünüz?",
                    "Düşüncelerinizin ve hislerinizin davranışınıza nasıl etki ettiğini düşünüyorsunuz?",
                    "Belirli bir durumda ne hissettiğinizi ve ne düşündüğünüzü anlatır mısınız?",
                    "Bu düşüncelerin doğru olduğunu size neyin gösterdiğini anlatır mısınız?",
                    "Bu düşüncenin doğru olmadığını size neyin gösterdiğini anlatır mısınız?",
                    "Bu düşüncenin sizi nasıl etkilediğini düşünüyorsunuz?",
                    "Bu durumla başa çıkmak için başka hangi yolları düşünüyorsunuz?",
                    "Daha önce bu tür durumlarla nasıl başa çıktınız?",
                    "Bu durumu değiştirmek için ne yapabileceğinizi düşünüyorsunuz?"
                ]
            },
            "remainingMessages": 10,
            "messages": [{
                    "variant": "user",
                    "message": "Merhaba",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "chatbot",
                    "message": "Merhaba! Size nasıl yardımcı olabilirim?",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "user",
                    "message": "Hava nasıl olacak?",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "chatbot",
                    "message": "Hava durumu hakkında bilgi vermek için şehir adınızı veya konumunuzu belirtir misiniz?",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "user",
                    "message": "İstanbul",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "chatbot",
                    "message": "İstanbul'da hava bugün parçalı bulutlu ve 28°C olacak.",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "user",
                    "message": "Teşekkür ederim!",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "chatbot",
                    "message": "Rica ederim! Başka bir sorunuz varsa sormaktan çekinmeyin.",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "user",
                    "message": "Yarın yağmur yağar mı?",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "chatbot",
                    "message": "Yarının hava durumu hakkında bilgi vermek için yine şehir adınızı veya konumunuzu belirtir misiniz?",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "user",
                    "message": "Ankara",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "chatbot",
                    "message": "Ankara'da yarın hava yağmurlu olacak, sıcaklık 22°C olacak.",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "user",
                    "message": "Nasıl giyinmeliyim?",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "chatbot",
                    "message": "Yarın yağmurlu bir gün olacak, şemsiye ve su geçirmez bir ceket giymeniz iyi olur.",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "user",
                    "message": "Teşekkür ederim!",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "chatbot",
                    "message": "Lütfen, başka bir sorunuz varsa sormaktan çekinmeyin. Size yardımcı olmaktan mutluluk duyarım.",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "user",
                    "message": "Hava durumu tahmini için hangi kaynağı kullanıyorsunuz?",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "chatbot",
                    "message": "Hava durumu verilerini güncel olarak AccuWeather API'si üzerinden alıyorum.",
                    "loading": false,
                    "isLast": false
                },
                {
                    "variant": "user",
                    "message": "Teşekkür ederim!",
                    "loading": false,
                    "isLast": true
                }
            ]
        }

        EOF;
        // kullanıcı id ye göre load yap son 10 mesaj
    }


    public function config()
    {
        return <<<CONFIG
        {
            "chatbotId": "UaRQtd7AOTaMXeRQGQRl",
            "botName": "Bilişsel Davranışçı Terapi Asistanı",
            "description": "Bu uygulama yapay zeka destekli sanal bilişsel davranışçı terapistidir. Sizelere psikoloji alanı kapsamında kısmi yardım sağlar.",
            "allowedDomains": ["localhost"],
            "labels": {
                "inputPlaceholder": "Mesajınız...",
                "firstMessage": "Merhaba ben bilişsel davranışçı terapist. Size nasıl yardımcı olabilirim?",
                "floatingButton": "Sanal Asistan",
                "close": "Kapat"
            },
            "color": "#a15a7b",
            "showButtonLabel": true,
            "alignment": "right",
            "horizontalMargin": 40,
            "verticalMargin": 40,
            "loginUrl": "https://terapivitrini.com/login"
        }    
        CONFIG;
    }
}
