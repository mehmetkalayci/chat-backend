@extends('layouts.app')


@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ $chatbot->name }} - {{ __('Chatbot Düzenle') }}
</h2>
@endsection

@section('content')
<div class="py-12">







    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">





        @if (session('success'))
        <div id="alert" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="ml-3 text-sm font-medium">
                {{ session('success') }}
            </div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert" aria-label="Kapat">
                <span class="sr-only">Kapat</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
        @endif

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div id="alert" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="ml-3 text-sm font-medium">
                {{ $error }}
            </div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
        @endforeach
        @endif


        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">


            <div class="mb-6 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px">
                    <li class="mr-2">
                        <a href="{{ route('chatbot.edit', ['chatbot' => $chatbot->chatbot_id]) }}" @if (Route::currentRouteName()=='chatbot.edit' ) class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page" @else class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" @endif>Chatbot Bilgileri</a>
                    </li>
                    <li class="mr-2">
                        <a href="{{ route('chatbot.questions', ['chatbot' => $chatbot->chatbot_id]) }}" @if (Route::currentRouteName()=='chatbot.questions' ) class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page" @else class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" @endif>Quiz Soruları</a>
                    </li>
                </ul>
            </div>


            <div class="max-w-xxl">
                <form action="{{ route('chatbot.update', $chatbot->chatbot_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-6">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chatbot Adı</label>
                        <input type="text" id="name" name="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required value="{{ $chatbot->name }}">
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chatbot Açıklama</label>
                        <input type="text" id="description" name="description" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required value="{{ $chatbot->description }}">
                    </div>

                    <!-- 
                    <div class="mb-6">
                        <label for="labels" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chatbot Labels</label>
                        <input type="text" id="labels" name="labels" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required value="{{ $chatbot->labels }}">
                    </div>
                     -->

                    <div class="mb-6">
                        <label for="inputPlaceholder" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chatbot Yanıt Kutusu (Input Placeholder)</label>
                        <input type="text" name="inputPlaceholder" id="inputPlaceholder" value="{{ json_decode($chatbot->labels)->inputPlaceholder }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
                    </div>

                    <div class="mb-6">
                        <label for="firstMessage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">İlk Mesaj (Karşılama Mesajı)</label>
                        <input type="text" name="firstMessage" id="firstMessage" value="{{ json_decode($chatbot->labels)->firstMessage }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
                    </div>

                    <div class="mb-6">
                        <label for="floatingButton" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chatbot Button Adı</label>
                        <input type="text" name="floatingButton" id="floatingButton" value="{{ json_decode($chatbot->labels)->floatingButton }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
                    </div>

                    <div class="mb-6">
                        <label for="close" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kapat Buton Adı</label>
                        <input type="text" name="close" id="close" value="{{ json_decode($chatbot->labels)->close }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
                    </div>


                    <div class="mb-6">
                        <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chatbot Renk</label>
                        <input type="text" id="color" name="color" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required value="{{ $chatbot->color }}">
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chatbot butonu yanında etiket gösterilsin mi?</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="show_button_label" value="0">
                            <input type="checkbox" id="show_button_label" name="show_button_label" {{ $chatbot->show_button_label ? 'checked' : '' }} value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Buton Etiketini Göster</span>
                        </label>
                    </div>


                    <div class="grid gap-6 mb-6 md:grid-cols-3">
                        <div class="mb-6">
                            <label for="horizontal_margin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Yatay Boşluk (px)</label>
                            <input type="text" id="horizontal_margin" name="horizontal_margin" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required value="{{ $chatbot->horizontal_margin }}">
                        </div>

                        <div class="mb-6">
                            <label for="vertical_margin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dikey Boşluk (px)</label>
                            <input type="text" id="vertical_margin" name="vertical_margin" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required value="{{ $chatbot->vertical_margin }}">
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hizalama</label>
                            <div class="flex">
                                <div class="flex items-center mr-6">
                                    <input id="alignment-1" type="radio" id="alignment" name="alignment" value="right" {{ $chatbot->alignment=='right' ? 'checked=true' : '' }}class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="alignment-1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sağ</label>
                                </div>
                                <div class="flex items-center mr-6">
                                    <input id="alignment-2" type="radio" id="alignment" name="alignment" value="left" {{ $chatbot->alignment=='left' ? 'checked=true' : '' }}class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="alignment-2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sol</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="login_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chatbot Login URL</label>
                        <input type="text" id="login_url" name="login_url" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required value="{{ $chatbot->login_url }}">
                    </div>

                    <div class="mb-6">
                        <label for="quiz_evaluation_prompt" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quiz Değerlendirme Promptu</label>
                        <textarea id="quiz_evaluation_prompt" name="quiz_evaluation_prompt" rows="5" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quiz Değerlendirme Promptu...">{{ $chatbot->quiz_evaluation_prompt }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="chatbot_prompt" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chatbot Kullanıcı Yanıtlama Promptu</label>
                        <textarea id="chatbot_prompt" name="chatbot_prompt" rows="25" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Chatbot Kullanıcı Yanıtlama Promptu...">{{ $chatbot->chatbot_prompt }}</textarea>
                    </div>

                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Kaydet</button>
                </form>



                <div class="mb-6 mt-6">
                    <label for="integration" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Entegrasyon Kodu</label>
                    <textarea id="integration" name="integration" rows="25" disabled class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Entegrasyon kodu..."><script>
      (window.ChatBotAI = window.ChatBotAI || {}),
        (ChatBotAI.init = function (c) {
          return new Promise(function (e, o) {
            var t = document.createElement("script");
            (t.type = "text/javascript"), (t.async = !0), (t.src = "https://fortytwogame.com/chat.js");
            const n = document.getElementsByTagName("script")[0];
            n.parentNode.insertBefore(t, n),
              t.addEventListener("load", function () {
                window.ChatBotAI.mount({
                  id: c.id,
                  apiBaseURL: c.apiBaseURL,
                  options: c.options,
                });
                let t;
                (t = function (n) {
                  return new Promise(function (e) {
                    if (document.querySelector(n))
                      return e(document.querySelector(n));
                    const o = new MutationObserver(function (t) {
                      document.querySelector(n) &&
                        (e(document.querySelector(n)), o.disconnect());
                    });
                    o.observe(document.body, { childList: !0, subtree: !0 });
                  });
                }),
                  t && t("#chatbotai-root").then(e).catch(o);
              }),
              t.addEventListener("error", function (t) {
                o(t.message);
              });
          });
        });
    </script>
    <script>
      ChatBotAI.init({
        id: "{{ $chatbot->chatbot_id }}",
        apiBaseURL: "https://fortytwogame.com/api/chatbot",
        options: {
            "botName": "{{ $chatbot->name }}",
            "description": "{{ $chatbot->description }}",
            "labels": {
                "inputPlaceholder": "{{ json_decode($chatbot->labels)->inputPlaceholder }}",
                "firstMessage": "{{ json_decode($chatbot->labels)->firstMessage }}",
                "floatingButton": "{{ json_decode($chatbot->labels)->floatingButton }}",
                "close": "{{ json_decode($chatbot->labels)->close }}",
            },
            "color": "{{ $chatbot->color }}",
            "alignment": "{{ $chatbot->alignment }}",
            "horizontalMargin": {{ $chatbot->horizontal_margin }},
            "verticalMargin": {{ $chatbot->vertical_margin }},
            "showButtonLabel": {{ $chatbot->show_button_label ? 'true' : 'false' }},
            "loginUrl": "{{ $chatbot->login_url }}"
        },
      }).then(() => {
        //  Script is safely loaded, you can do whatever you want from here with bot
      });
    </script></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // HTML'deki kapatma düğmesini yakalama
        const closeButtons = document.querySelectorAll('#alert');

        // Her kapatma düğmesine tıklama olayı ekle
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Kapatma düğmesinin üstündeki uyarıyı bul
                const alert = button.closest('#alert');

                // Uyarıyı gizle
                alert.style.display = 'none';
            });
        });


        const accordions = document.querySelectorAll(".accordion");

        for (const accordion of accordions) {
            accordion.addEventListener("click", (event) => {
                const button = event.target;
                const body = button.nextElementSibling;

                if (body.classList.contains("is-open")) {
                    body.classList.remove("is-open");
                } else {
                    body.classList.add("is-open");
                }
            });
        }
    });
</script>
@endsection