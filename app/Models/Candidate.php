<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'publish_category', 'name', 'gender', 'birth_date',
        'nationality', 'last_education', 'work_history', 'self_presentation',
        'personal_preference', 'profile_picture_link', 'self_introduction_video_link', 'cv_link', 'created_by', 'updated_by',
        'college', 'japanese_language_qualification', 'desired_job_type', 'other_request', 'publish_category',
    ];

    protected $casts = [
        'birth_date' => 'date:Y-m-d',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($candidate) {
            if ($candidate->isDirty('name')) {
                // Update student's user name if exists
                if ($candidate->student && $candidate->student->user) {
                    $candidate->student->user->update(['name' => $candidate->name]);
                }
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'nationality');
    }

    public function qualifications()
    {
        return $this->belongsToMany(Qualification::class, 'candidate_qualification')->withTimestamps();
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function desiredJobType()
    {
        return $this->belongsTo(VacancyCategory::class, 'desired_job_type');
    }

    public function selfPresentationHistories()
    {
        return $this->hasMany(SelfPresentationHistory::class);
    }

    public function interviewPractices()
    {
        return $this->hasMany(InterviewPractice::class);
    }

    public function interviewWritingPractices()
    {
        return $this->hasMany(InterviewWritingPractice::class);
    }

    public function tokenUsage()
    {
        return $this->hasOne(CandidateTokenUsage::class);
    }

    public function generateSelfPresentation($userText, $aiGeneratedText, $tokenUsage)
    {
        $selfPresentation = $this->selfPresentationHistories()->create([
            'user_text' => $userText,
            'ai_generated_text' => $aiGeneratedText,
            'generation_token_usage' => $tokenUsage,
        ]);

        $this->updateTokenUsage($tokenUsage);

        return $selfPresentation;
    }

    public function saveSelfPresentation(SelfPresentationHistory $selfPresentation, $savedText)
    {
        $selfPresentation->saved_text = $savedText;
        $selfPresentation->save();
    }

    private function updateTokenUsage($tokens)
    {
        $tokenUsage = $this->tokenUsage;
        if (! $tokenUsage) {
            $tokenUsage = $this->tokenUsage()->create([
                'last_reset_date' => now(),
            ]);
        }
        $tokenUsage->incrementUsage($tokens);
    }

    public function favoriteVacancies()
    {
        return $this->belongsToMany(Vacancy::class, 'candidate_favorite_vacancies')->withTimestamps();
    }
}
