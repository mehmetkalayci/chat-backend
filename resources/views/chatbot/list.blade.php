@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @section('header')
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Chatbot Listesi') }}
            </h2>
            @endsection

            <div class="mb-4">
                <a href="{{ route('chatbot.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Yeni Chatbot Ekle</a>
            </div>
            <div class="mb-4">
                <input type="text" class="border rounded p-2 w-full" placeholder="Chatbot Adı Ara...">
            </div>

            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="text-left">Chatbot ID</th>
                        <th class="text-left">Chatbot Ad</th>
                        <th class="text-left">Açıklama</th>
                        <th class="text-left">Input Metni</th>
                        <th class="text-left">İlk Mesaj</th>
                        <th class="text-left">Chatbot Butonu Adı</th>
                        <th class="text-left">Kapat Butonu Adı</th>
                        <th class="text-left">Renk</th>
                        <th class="text-left">Buton Etiketi Göster?</th>
                        <th class="text-left">Hizalama</th>
                        <th class="text-left">Yatay Kenar Boşluğu</th>
                        <th class="text-left">Dikey Kenar Boşluğu</th>
                        <th class="text-left">Kullanıcı Giriş URL'i</th>
                        <th class="text-left">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chatbots as $chatbot)
                    <tr>
                        <td>{{ $chatbot->chatbot_id }}</td>
                        <td>{{ $chatbot->name }}</td>
                        <td>{{ $chatbot->description }}</td>
                        <td>{{ $chatbot->input_placeholder }}</td>
                        <td>{{ $chatbot->first_message }}</td>
                        <td>{{ $chatbot->floating_button_name }}</td>
                        <td>{{ $chatbot->close_button_name }}</td>
                        <td>{{ $chatbot->chatbot_color }}</td>
                        <td>{{ $chatbot->show_button_label ? 'Evet' : 'Hayır' }}</td>
                        <td>{{ $chatbot->alignment }}</td>
                        <td>{{ $chatbot->horizontal_margin }}</td>
                        <td>{{ $chatbot->vertical_margin }}</td>
                        <td>{{ $chatbot->login_url }}</td>
                        <td>
                            <a href="{{ route('chatbot.show', $chatbot->chatbot_id) }}" class="text-blue-500 hover:underline">Düzenle</a>
                            <button class="text-red-500 hover:underline" @click="deleteChatbot({{ $chatbot->chatbot_id }})">Sil</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection