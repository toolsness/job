<?php

namespace App\Models;

use App\Enum\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vacancy_id', 'interview_date', 'interview_start_time', 'reservation_status',
    ];

    protected $casts = [
        'interview_date' => 'date',
        'interview_start_time' => 'datetime',
        'reservation_status' => ReservationStatus::class,
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }

    public function getInterviewStartTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i:s') : null;
    }

    public function setInterviewStartTimeAttribute($value)
    {
        if ($value instanceof Carbon) {
            $this->attributes['interview_start_time'] = $value->format('Y-m-d H:i:s');
        } elseif (is_string($value)) {
            $this->attributes['interview_start_time'] = Carbon::parse($value)->format('Y-m-d H:i:s');
        } else {
            $this->attributes['interview_start_time'] = null;
        }
    }

    // New method to get formatted interview date
    public function getFormattedInterviewDate()
    {
        return Carbon::parse($this->interview_date)->format('l jS \\of F Y');
    }

    // New method to get formatted interview start time
    public function getFormattedInterviewStartTime()
    {
        return Carbon::parse($this->interview_start_time)->format('h:i A');
    }
}
