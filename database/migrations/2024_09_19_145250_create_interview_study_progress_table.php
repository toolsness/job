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
        Schema::create('interview_study_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedTinyInteger('orientation_video_progress')->default(0);
            $table->unsignedTinyInteger('profile_registration_progress')->default(0);
            $table->unsignedTinyInteger('interview_answer_creation_progress')->default(0);
            $table->unsignedTinyInteger('interview_response_practice_progress')->default(0);
            $table->unsignedTinyInteger('mock_interview_progress')->default(0);
            $table->unsignedTinyInteger('final_interview_progress')->default(0);

            $table->date('orientation_video_date')->nullable();
            $table->date('profile_registration_date')->nullable();
            $table->date('interview_answer_creation_date')->nullable();
            $table->date('interview_response_practice_date')->nullable();
            $table->date('mock_interview_date')->nullable();
            $table->date('final_interview_date')->nullable();

            $table->timestamps();
            $table->foreign('student_id')
                ->references('id')
                ->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_study_progress');
    }
};
