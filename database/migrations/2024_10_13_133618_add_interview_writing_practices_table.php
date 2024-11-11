<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interview_writing_practices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->enum('question_type', ['self_introduction', 'motivation', 'advantages_disadvantages', 'future_plans', 'questions_to_companies']);
            $table->text('question');
            $table->text('user_answer');
            $table->text('improved_answer');
            $table->integer('overall_score');
            $table->integer('content_score');
            $table->integer('language_score');
            $table->integer('structure_score');
            $table->text('overall_feedback');
            $table->json('errors');
            $table->enum('practice_mode', ['text', 'voice_to_text', 'text_to_voice', 'voice_to_voice'])->default('text');
            $table->string('user_voice_url')->nullable();
            $table->string('ai_voice_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interview_writing_practices');
    }
};
