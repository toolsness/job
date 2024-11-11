<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ai_voice_practice_results', function (Blueprint $table) {
            $table->integer('content_score')->after('overall_score')->nullable();
            $table->integer('language_score')->after('content_score')->nullable();
            $table->integer('pronunciation_score')->after('language_score')->nullable();
            $table->json('evaluation')->after('errors')->nullable();
        });
    }

    public function down()
    {
        Schema::table('ai_voice_practice_results', function (Blueprint $table) {
            $table->dropColumn(['content_score', 'language_score', 'pronunciation_score', 'evaluation']);
        });
    }
};
