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
        Schema::create('chatbot_tests', function (Blueprint $table) {
            $table->id('test_id');
            $table->string('name');
            $table->string('description');
            $table->boolean('is_published')->default(false);
            $table->uuid('chatbot_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_tests');
    }
};
