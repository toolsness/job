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
        Schema::create('interview_practice_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_practice_id')->constrained()->onDelete('cascade');
            $table->enum('option_type', ['self_introduction', 'motivation', 'advantages_disadvantages', 'future_plans', 'questions_to_companies']);
            $table->text('user_text');
            $table->text('ai_generated_text');
            $table->text('saved_text')->nullable();
            $table->integer('score');
            $table->text('feedback');
            $table->integer('generation_token_usage')->default(0);
            $table->integer('evaluation_token_usage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_practice_options');
    }
};
