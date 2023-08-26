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
            $table->string('description');
            $table->string('input_placeholder');
            $table->string('first_message');
            $table->string('floating_button_name');
            $table->string('close_button_name');
            $table->string('chatbot_color');
            $table->boolean('show_button_label')->default(false);
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
