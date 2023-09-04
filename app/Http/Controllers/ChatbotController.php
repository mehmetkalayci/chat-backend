<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        //
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
        //
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
}
