@extends('layouts.app')


@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ $chatbot->name }} - {{ __('Chatbot Düzenle') }}
</h2>
@endsection

@section('content')
<div class="container mx-auto">
    <form action="{{ route('chatbot.show', $chatbot->chatbot_id) }}" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold">Chatbot Adı</label>
            <input type="text" name="name" id="name" class="form-input mt-1 block w-full" value="{{ $chatbot->name }}" />
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-semibold">Açıklama</label>
            <textarea name="description" id="description" class="form-input mt-1 block w-full h-20">{{ $chatbot->description }}</textarea>
        </div>

        <div class="mb-4">
            <label for="input_placeholder" class="block text-gray-700 font-semibold">Input Metni</label>
            <input type="text" name="input_placeholder" id="input_placeholder" class="form-input mt-1 block w-full" value="{{ $chatbot->input_placeholder }}" />
        </div>

        <div class="mb-4">
            <label for="first_message" class="block text-gray-700 font-semibold">İlk Mesaj</label>
            <input type="text" name="first_message" id="first_message" class="form-input mt-1 block w-full" value="{{ $chatbot->first_message }}" />
        </div>

        <div class="mb-4">
            <label for="floating_button_name" class="block text-gray-700 font-semibold">Chatbot Buttonu Adı</label>
            <input type="text" name="floating_button_name" id="floating_button_name" class="form-input mt-1 block w-full" value="{{ $chatbot->floating_button_name }}" />
        </div>

        <div class="mb-4">
            <label for="close_button_name" class="block text-gray-700 font-semibold">Kapatma Buttonu Adı</label>
            <input type="text" name="close_button_name" id="close_button_name" class="form-input mt-1 block w-full" value="{{ $chatbot->close_button_name }}" />
        </div>

        <div class="mb-4">
            <label for="chatbot_color" class="block text-gray-700 font-semibold">Chatbot Rengi</label>
            <input type="text" name="chatbot_color" id="chatbot_color" class="form-input mt-1 block w-full" value="{{ $chatbot->chatbot_color }}" />
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Buton Etiketi Göster?</label>
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="show_button_label" value="true" class="form-radio" {{ $chatbot->show_button_label == 'true' ? 'checked' : '' }} />
                    <span class="ml-2">Evet</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" name="show_button_label" value="false" class="form-radio" {{ $chatbot->show_button_label == 'false' ? 'checked' : '' }} />
                    <span class="ml-2">Hayır</span>
                </label>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Hizalama</label>
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="alignment" value="right" class="form-radio" {{ $chatbot->alignment == 'right' ? 'checked' : '' }} />
                    <span class="ml-2">Sağ</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" name="alignment" value="left" class="form-radio" {{ $chatbot->alignment == 'left' ? 'checked' : '' }} />
                    <span class="ml-2">Sol</span>
                </label>
            </div>
        </div>

        <div class="mb-4">
            <label for="horizontal_margin" class="block text-gray-700 font-semibold">Yatay Kenar Boşluğu</label>
            <input type="number" name="horizontal_margin" id="horizontal_margin" class="form-input mt-1 block w-full" value="{{ $chatbot->horizontal_margin }}" />
        </div>

        <div class="mb-4">
            <label for="vertical_margin" class="block text-gray-700 font-semibold">Dikey Kenar Boşluğu</label>
            <input type="number" name="vertical_margin" id="vertical_margin" class="form-input mt-1 block w-full" value="{{ $chatbot->vertical_margin }}" />
        </div>

        <div class="mb-4">
            <label for="login_url" class="block text-gray-700 font-semibold">Kullanıcı Giriş URL'i</label>
            <input type="text" name="login_url" id="login_url" class="form-input mt-1 block w-full" value="{{ $chatbot->login_url }}" />
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Güncelle</button>
        </div>
    </form>
</div>
@endsection