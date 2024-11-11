<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiVoicePracticeResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_writing_practice_id',
        'user_voice_url',
        'transcribed_text',
        'errors',
        'overall_score',
        'content_score',
        'language_score',
        'pronunciation_score',
        'feedback',
        'evaluation',
    ];

    protected $casts = [
        'errors' => 'array',
        'evaluation' => 'array',
    ];

    public function interviewWritingPractice()
    {
        return $this->belongsTo(InterviewWritingPractice::class);
    }
}
