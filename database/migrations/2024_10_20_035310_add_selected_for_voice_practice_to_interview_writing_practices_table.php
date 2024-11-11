<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interview_writing_practices', function (Blueprint $table) {
            $table->boolean('selected_for_voice_practice')->default(false);
        });
    }

    public function down()
    {
        Schema::table('interview_writing_practices', function (Blueprint $table) {
            $table->dropColumn('selected_for_voice_practice');
        });
    }
};
