<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->json('ai_models')->nullable();
            $table->json('system_prompts')->nullable();
            $table->json('user_prompts')->nullable();
            $table->json('example_samples')->nullable();
            $table->boolean('use_custom_prompts')->default(false);
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['ai_models', 'system_prompts', 'user_prompts', 'example_samples', 'use_custom_prompts']);
        });
    }
};
