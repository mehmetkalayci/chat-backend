@extends('layouts.app')


@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ $chatbot->name }} - {{ __('Chatbot Quiz Soruları Düzenle') }}
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
                <form action="{{ route('chatbot.questionsUpdate', $chatbot->chatbot_id) }}" method="POST">
                    @csrf
                    @method('PUT')


                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Tip
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Soru/Mesaj
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        İşlemler
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $question)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <select id="type-{{ $question->chatbot_question_id }}" name="type[{{ $question->chatbot_question_id }}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="chatbot_message" {{ $question->type == 'chatbot_message' ? 'selected' : ''}}>Mesaj</option>
                                                <option value="chatbot_question" {{ $question->type == 'chatbot_question' ? 'selected' : ''}}>Soru</option>
                                            </select>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4">
                                        <div>
                                            <input type="text" id="value-{{ $question->chatbot_question_id }}" name="values[{{ $question->chatbot_question_id }}]" value="{{ $question->value }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Sorunuz ya da Mesajınız" required />
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <!-- <form method="POST" action="{{ route('chatbot.deleteQuestion', ['chatbot' => $chatbot, 'question' => $question]) }}"> -->
                                        <!-- @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block p-2 text-red-600 rounded-lg hover:text-red-500">
                                                Sil
                                            </button> -->
                                        <button type="button" class="delete-button inline-block p-2 text-red-600 rounded-lg hover:text-red-500" data-question-id="{{ $question->chatbot_question_id }}">
                                            Sil
                                        </button> <!-- </form> -->
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-4"></th>
                                    <td class="px-6 py-4"></td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="ekleButton font-medium text-blue-600 dark:text-blue-500 hover:underline">Satır Ekle</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="mt-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript ile silme işlemi -->
<script>
    $(document).ready(function() {
        $(document).on('click', '.delete-button', function() {
            // Kullanıcıya silme işleminden önce bir onay sor
            var confirmed = confirm('Bu soruyu/mesajı silmek istediğinize emin misiniz?');

            if (confirmed) {
                var questionId = $(this).attr('data-question-id');

                if (typeof questionId === 'undefined') {
                    // data-question-id özelliği varsa, satırı kaldırın
                    $(this).closest('tr').remove();
                }

                // AJAX isteğiyle silme işlemi yapın
                $.ajax({
                    method: 'POST',
                    url: '/chatbot/{{ $chatbot->chatbot_id }}/questions', // Uygun URL'yi ayarlayın
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token'ı alın
                    },
                    data: {
                        'chatbot_question_id': questionId,
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        '_method': 'DELETE' // DELETE işlemini simüle ediyoruz
                    },
                    success: function(response) {
                        // Başarılı bir şekilde silindiğinde yapılacak işlemleri burada yapabilirsiniz
                        console.log('Silme işlemi başarılı.');
                        window.location.href = this.url;
                        // İlgili satırı tablodan kaldırabilirsiniz veya sayfayı yenileyebilirsiniz.
                    },
                    error: function() {
                        // Silme işlemi başarısız olduğunda yapılacak işlemleri burada yapabilirsiniz
                        console.error('Silme işlemi başarısız.');
                        window.location.href = this.url;

                    }
                });


            }
        });


        // Satır ekleme işlemi
        $(".ekleButton").click(function() {
            // Yeni bir satır oluşturun
            var newRow = $("<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'></tr>");

            // Satır içeriğini doldurun
            newRow.html('<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"> \
                        <div> \
                            <select name="type-new[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"> \
                                <option value="chatbot_message">Mesaj</option> \
                                <option value="chatbot_question" selected="">Soru</option> \
                            </select> \
                        </div> \
                    </th> \
                    <td class="px-6 py-4"> \
                        <div> \
                            <input type="text" name="values-new[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Sorunuz ya da Mesajınız" required=""> \
                        </div> \
                    </td> \
                    <td class="px-6 py-4"> \
                        <button type="button" class="delete-button inline-block p-2 text-red-600 rounded-lg hover:text-red-500"> \
                            Sil \
                        </button> \
                    </td>');

            // Yeni satırı tabloya ekleyin
            $("table tr:last").before(newRow);
            return false;
        });
    });
</script>



@endsection