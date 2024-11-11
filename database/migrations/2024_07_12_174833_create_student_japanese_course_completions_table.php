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
        Schema::create('student_japanese_course_completions', function (Blueprint $table) {
            $table->id();
        $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
        $table->unsignedBigInteger('japanese_study_course_id');
        $table->timestamp('completed_at');
        $table->timestamps();
        $table->foreignId('created_by')->nullable()->constrained('users');
        $table->foreignId('updated_by')->nullable()->constrained('users');

        // Add this line with a custom constraint name
        $table->foreign('japanese_study_course_id', 'course_id_foreign')
              ->references('id')
              ->on('japanese_study_courses')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_japanese_course_completions');
    }
};
