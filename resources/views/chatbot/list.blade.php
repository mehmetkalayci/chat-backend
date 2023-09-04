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

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Chatbot ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Chatbot Ad
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Açıklama
                            </th>
                            <th scope="col" class="px-6 py-3">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chatbots as $chatbot)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $chatbot->chatbot_id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $chatbot->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $chatbot->description }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('chatbot.edit', $chatbot->chatbot_id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Göster/Düzenle</a>
                                <button @click="deleteChatbot({{ $chatbot->chatbot_id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">Sil</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection