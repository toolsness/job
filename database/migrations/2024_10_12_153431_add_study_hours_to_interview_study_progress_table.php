<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interview_study_progress', function (Blueprint $table) {
            // Check if columns exist before dropping
            if (Schema::hasColumn('interview_study_progress', 'total_study_hours')) {
                $table->dropColumn('total_study_hours');
            }
            if (Schema::hasColumn('interview_study_progress', 'interview_answer_writing_hours')) {
                $table->dropColumn('interview_answer_writing_hours');
            }
            if (Schema::hasColumn('interview_study_progress', 'interview_answer_practice_hours')) {
                $table->dropColumn('interview_answer_practice_hours');
            }

            // Add new column
            $table->json('study_sessions')->nullable();
        });
    }

    public function down()
    {
        Schema::table('interview_study_progress', function (Blueprint $table) {
            // Add back the columns if they don't exist
            if (!Schema::hasColumn('interview_study_progress', 'total_study_hours')) {
                $table->integer('total_study_hours')->default(0);
            }
            if (!Schema::hasColumn('interview_study_progress', 'interview_answer_writing_hours')) {
                $table->integer('interview_answer_writing_hours')->default(0);
            }
            if (!Schema::hasColumn('interview_study_progress', 'interview_answer_practice_hours')) {
                $table->integer('interview_answer_practice_hours')->default(0);
            }

            // Drop the new column
            $table->dropColumn('study_sessions');
        });
    }
};
