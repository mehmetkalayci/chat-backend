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
     * Display the specified resource.
     */
    public function show(Chatbot $chatbot)
    {
        return view('chatbot.show', compact('chatbot'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chatbot $chatbot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chatbot $chatbot)
    {
        //
    }
}
