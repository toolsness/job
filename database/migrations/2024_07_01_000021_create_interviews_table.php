<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\InterviewStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->foreignId('vacancy_id')->constrained('vacancies')->onDelete('cascade');
            $table->foreignId('interview_schedule_id')->nullable()->constrained('interview_schedules')->onDelete('cascade');
            $table->foreignId('incharge_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('implementation_date')->nullable();
            $table->dateTime('implementation_start_time')->nullable();
            $table->string('status')->nullable()->default(InterviewStatus::APPLICATION_FROM_STUDENTS->value);
            $table->text('reason')->nullable();
            $table->string('zoom_link')->nullable();
            $table->date('booking_request_date_student')->nullable();
            $table->date('booking_request_date_company')->nullable();
            $table->date('booking_confirmation_date')->nullable();
            $table->date('result_notification_date')->nullable();
            $table->enum('result', ['Pending', 'Passing', 'Failed', 'Cancelled', 'NotApplicable'])->default('NotApplicable')->nullable();
            $table->date('employment_contract_procedure_application_date')->nullable();
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
        Schema::dropIfExists('interviews');
    }
};
