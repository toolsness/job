<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ai_voice_practice_results', function (Blueprint $table) {
            $table->dropForeign(['interview_practice_option_id']);
            $table->dropColumn('interview_practice_option_id');
            $table->foreignId('interview_writing_practice_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('ai_voice_practice_results', function (Blueprint $table) {
            $table->dropForeign(['interview_writing_practice_id']);
            $table->dropColumn('interview_writing_practice_id');
            $table->foreignId('interview_practice_option_id')->constrained()->onDelete('cascade');
        });
    }
};
