<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'study_category', 'activity_date', 'prompt_link',
        'conversation_script_link', 'conversation_audio_link'
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
