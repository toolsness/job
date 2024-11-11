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
        Schema::create('interview_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('study_category', ['Orientation', 'InterviewAnswerCreation', 'InterviewAnswerPractice', 'MockInterview']);
            $table->date('activity_date');
            $table->string('prompt_link')->nullable();
            $table->string('conversation_script_link')->nullable();
            $table->string('conversation_audio_link')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_studies');
    }
};
