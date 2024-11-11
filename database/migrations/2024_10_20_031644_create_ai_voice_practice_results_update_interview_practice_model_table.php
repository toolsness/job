<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ai_voice_practice_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_practice_option_id')->constrained()->onDelete('cascade');
            $table->string('user_voice_url');
            $table->text('transcribed_text');
            $table->json('errors');
            $table->integer('overall_score');
            $table->text('feedback');
            $table->timestamps();
        });

        Schema::table('interview_practice_options', function (Blueprint $table) {
            $table->boolean('selected_for_voice_practice')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_voice_practice_results');

        Schema::table('interview_practice_options', function (Blueprint $table) {
            $table->dropColumn('selected_for_voice_practice');
        });
    }
};
