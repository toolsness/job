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
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('college')->nullable();
            $table->string('japanese_language_qualification')->nullable();
            $table->unsignedBigInteger('desired_job_type')->nullable();
            $table->string('other_request')->nullable();
            $table->foreign('desired_job_type')
                ->references('id')
                ->on('vacancy_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['college', 'japanese_language_qualification', 'desired_job_type', 'other_request']);
        });
    }
};
