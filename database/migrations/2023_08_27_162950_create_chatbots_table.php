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
            $table->uuid('chatbot_id')->primary();
            $table->string('name');
            $table->text('description');
            $table->text('prompt');
            $table->text('openai_api_key');
            $table->string('color');
            $table->string('placeholder');
            $table->string('btn_name');
            $table->boolean('show_btn')->default(false);
            $table->string('first_message');
            $table->enum('alignment', ['right', 'left']);
            $table->integer('horizontal_margin')->default(20);
            $table->integer('vertical_margin')->default(20);
            $table->string('login_url');
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
