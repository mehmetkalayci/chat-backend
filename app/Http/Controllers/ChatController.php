<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

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

    public function ask(Request $request)
    {
        $question = $request->question;
        $token = $request->token;

        if ($token === "1234") {
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

        // kullanıcı id ye göre sil
    }

    public function load(Request $request)
    {
        $token = $request->token;

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
