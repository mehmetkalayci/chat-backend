<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chatbots', function (Blueprint $table) {
            $table->uuid('chatbot_id');
            $table->string('name');
            $table->text('description');
            $table->json('labels');
            $table->string('color');
            $table->boolean('show_button_label');
            $table->enum('alignment', ['right', 'left']);
            $table->integer('horizontal_margin');
            $table->integer('vertical_margin');
            $table->string('login_url');
            $table->longText('chatbot_prompt');
            $table->longText('quiz_evaluation_prompt');
            $table->text('openai_api_key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbots');
    }
};
