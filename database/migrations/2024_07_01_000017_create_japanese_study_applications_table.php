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
        Schema::create('japanese_study_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('japanese_study_course_id')->constrained('japanese_study_courses')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('month_of_use');
            $table->date('start_date');
            $table->date('estimated_end_date');
            $table->integer('monthly_amount');
            $table->dateTime('contract_date');
            $table->string('credit_card_company');
            $table->string('credit_card_number');
            $table->string('credit_card_expiry');
            $table->string('credit_card_security_number');
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
        Schema::dropIfExists('japanese_study_applications');
    }
};
