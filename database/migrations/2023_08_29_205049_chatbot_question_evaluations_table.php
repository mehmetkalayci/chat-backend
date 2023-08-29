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
        Schema::create('chatbot_question_evaluations', function (Blueprint $table) {
            $table->id('chatbot_question_evaluation_id');
            $table->uuid('chatbot_id');
            $table->unsignedBigInteger('chatbot_user_id');
            $table->text('evaluation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_question_responses');
    }
};
