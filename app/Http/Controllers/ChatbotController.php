<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use App\Models\ChatbotQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChatbotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chatbots = DB::table('chatbots')->get();
        return view('chatbot.list')->with('chatbots', $chatbots);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('chatbot.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'inputPlaceholder' => 'required',
            'firstMessage' => 'required',
            'floatingButton' => 'required',
            'close' => 'required',
            'color' => 'required',
            'horizontal_margin' => 'required|numeric',
            'vertical_margin' => 'required|numeric',
            'alignment' => 'required',
            'login_url' => 'required',
            'quiz_evaluation_prompt' => 'required',
            'chatbot_prompt' => 'required',
        ]);


        // manually create a chatbot
        $chatbot = new Chatbot();
        $chatbot->chatbot_id = \Illuminate\Support\Str::uuid();
        $chatbot->name = $request->name;
        $chatbot->description = $request->description;
        
        $chatbot->labels = json_encode([
            "inputPlaceholder" => $request->inputPlaceholder,
            "firstMessage" => $request->firstMessage,
            "floatingButton" => $request->floatingButton,
            "close" => $request->close
        ]);

        $chatbot->color = $request->color;
        $chatbot->show_button_label = $request->showButtonLabel == '1' ? true : false;
        $chatbot->alignment = $request->alignment;
        $chatbot->horizontal_margin = $request->horizontal_margin;
        $chatbot->vertical_margin = $request->vertical_margin;
        $chatbot->login_url = $request->login_url;
        $chatbot->quiz_evaluation_prompt = $request->quiz_evaluation_prompt;
        $chatbot->chatbot_prompt = $request->chatbot_prompt;
        $chatbot->openai_api_key = '';

        $chatbot_id =  $chatbot->chatbot_id;

        // save chatbot and check if it was successful
        if ($chatbot->save()) {
            return redirect()->route('chatbot.edit', ['chatbot' => $chatbot_id])->with('success', 'Chatbot başarıyla kaydedildi.');
        } else {
            return redirect()->route('chatbot.create')->with('error', 'Veri kaydedilirken hata oluştu.');
        }
    }

    /**
     * Display the js of specified resource.
     */
    public function json(Chatbot $chatbot)
    {
        // JSON nesnesi oluşturun
        $data = [
            "botId" => (string)$chatbot->chatbot_id,
            "botName" => $chatbot->name,
            "description" => $chatbot->description,
            "labels" => [
                "inputPlaceholder" => $chatbot->input_placeholder,
                "firstMessage" => $chatbot->first_message,
                "floatingButton" => $chatbot->floating_button_name,
                "close" => $chatbot->close_button_name
            ],
            "color" => $chatbot->chatbot_color,
            "showButtonLabel" => (bool)$chatbot->show_button_label,
            "alignment" => $chatbot->alignment,
            "horizontalMargin" =>  $chatbot->horizontal_margin,
            "verticalMargin" =>  $chatbot->vertical_margin,
            "loginUrl" =>  $chatbot->login_url
        ];

        // JSON nesnesini döndürün
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chatbot $chatbot)
    {
        if ($chatbot->labels == null) {
            $veri = json_decode('{"inputPlaceholder":"Mesaj\u0131n\u0131z...","firstMessage":"Ho\u015fgeldiniz, ben yapay zeka asistan\u0131n\u0131z psikolog Enver.","floatingButton":"Sanal Asistan","close":"Kapat"}');
            $chatbot->labels  = $veri;
        }
        return view('chatbot.edit', compact('chatbot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chatbot $chatbot)
    {
        // validate request
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'inputPlaceholder' => 'required',
            'firstMessage' => 'required',
            'floatingButton' => 'required',
            'close' => 'required',
            'color' => 'required',
            'horizontal_margin' => 'required|numeric',
            'vertical_margin' => 'required|numeric',
            'alignment' => 'required',
            'login_url' => 'required',
            'quiz_evaluation_prompt' => 'required',
            'chatbot_prompt' => 'required',
        ]);

        $chatbot->update($request->all());
        return redirect()->route('chatbot.edit', ['chatbot' => $chatbot->chatbot_id])->with('success', 'Veri başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chatbot $chatbot)
    {
        $chatbot->delete();
        return redirect()->route('chatbot.index')->with('success', 'Chatbot başarıyla silindi.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function questions(Chatbot $chatbot)
    {
        $chatbot_id = $chatbot->chatbot_id;
        $questions = DB::table('chatbot_questions')->where('chatbot_id', $chatbot_id)->get();

        return view('chatbot.questions', compact('questions', 'chatbot'));
    }

    public function questionsUpdate(Chatbot $chatbot, Request $request)
    {

        $questionTypesNew = $request->input('type-new');
        $questionValuesNew = $request->input('values-new');

        $questionTypes = $request->input('type');
        $questionValues = $request->input('values');

        foreach ($questionValues as $questionId => $value) {
            // Veritabanında ilgili chatbot_question_id'ye sahip kaydı bulun
            $question = ChatbotQuestion::find($questionId);

            // Eğer kayıt varsa, tipini güncelleyin
            if ($question) {
                $question->type = $questionTypes[$questionId];
                $question->value = $value;
                $question->save();
            }
        }

        if ($questionTypesNew != null && $questionValuesNew != null) {
            foreach ($questionValuesNew as $questionId => $value) {
                // Create new question
                $question = new ChatbotQuestion();
                $question->chatbot_id = $chatbot->chatbot_id;
                $question->type = $questionTypesNew[$questionId];
                $question->value = $value;
                $question->save();
            }
        }



        return redirect()->route('chatbot.questions', ['chatbot' => $chatbot->chatbot_id])->with('success', 'Sorular/Mesajlar kaydedildi.');
    }

    public function deleteQuestion(Chatbot $chatbot, ChatbotQuestion $question, Request $request)
    {
        $questionId = $request->chatbot_question_id;

        try {
            ChatbotQuestion::where('chatbot_question_id', $questionId)->delete();
            return redirect()->route('chatbot.questions', ['chatbot' => $chatbot->chatbot_id])->with('success', 'Soru silindi.');
        } catch (\Throwable $th) {
            return redirect()->route('chatbot.questions', ['chatbot' => $chatbot->chatbot_id])->with('error', 'Soru silinirken hata oluştu.' . $th->getMessage());
        }
    }
}
