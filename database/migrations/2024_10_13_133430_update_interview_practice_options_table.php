<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interview_practice_options', function (Blueprint $table) {
            // Drop existing columns that will be replaced
            $table->dropColumn(['ai_generated_text', 'saved_text', 'score', 'feedback']);

            // Add new columns
            $table->text('question')->after('option_type');
            $table->text('improved_answer')->after('user_text');
            $table->integer('overall_score')->after('improved_answer');
            $table->integer('content_score')->after('overall_score');
            $table->integer('language_score')->after('content_score');
            $table->integer('structure_score')->after('language_score');
            $table->text('overall_feedback')->after('structure_score');
            $table->json('errors')->after('overall_feedback');
            $table->enum('practice_mode', ['text', 'voice_to_text', 'text_to_voice', 'voice_to_voice'])->default('text')->after('errors');
            $table->string('user_voice_url')->nullable()->after('practice_mode');
            $table->string('ai_voice_url')->nullable()->after('user_voice_url');
        });
    }

    public function down()
    {
        Schema::table('interview_practice_options', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn([
                'question',
                'improved_answer',
                'overall_score',
                'content_score',
                'language_score',
                'structure_score',
                'overall_feedback',
                'errors',
                'practice_mode',
                'user_voice_url',
                'ai_voice_url'
            ]);

            // Add back original columns
            $table->text('ai_generated_text')->after('user_text');
            $table->text('saved_text')->nullable()->after('ai_generated_text');
            $table->integer('score')->after('saved_text');
            $table->text('feedback')->after('score');
        });
    }
};
