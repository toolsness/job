<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Support\Carbon;

class InterviewStudyProgress extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'study_sessions' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function addStudyTime($seconds, $type)
    {
        $studySessions = $this->study_sessions ?? [
            'total' => 0,
            'practice' => 0,
            'writing' => 0,
        ];

        $studySessions['total'] += $seconds;
        $studySessions[$type] += $seconds;

        $this->study_sessions = $studySessions;
        $this->save();
    }

    public function getStudyTime($type = 'total')
    {
        return $this->study_sessions[$type] ?? 0;
    }
}
