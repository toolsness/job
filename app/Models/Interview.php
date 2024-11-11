<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\InterviewStatus;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id', 'vacancy_id', 'interview_schedule_id', 'incharge_user_id', 'implementation_date',
        'implementation_start_time', 'status', 'zoom_link', 'booking_request_date_student',
        'booking_request_date_company', 'booking_confirmation_date', 'result_notification_date',
        'result', 'employment_contract_procedure_application_date', 'reason', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'implementation_date' => 'date',
        'implementation_start_time' => 'datetime',
        'booking_request_date_student' => 'date',
        'booking_request_date_company' => 'date',
        'booking_confirmation_date' => 'date',
        'result_notification_date' => 'date',
        'employment_contract_procedure_application_date' => 'date',
        'status' => InterviewStatus::class,
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function interviewSchedule()
    {
        return $this->belongsTo(InterviewSchedule::class);
    }

    public function memos()
    {
        return $this->hasMany(InterviewMemo::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function inchargeUser()
    {
        return $this->belongsTo(User::class, 'incharge_user_id');
    }
}
