<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'name_kanji', 'name_katakana', 'name_japanese', 'contact_phone_number', 'created_by', 'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }

    public function japaneseStudyApplications()
    {
        return $this->hasMany(JapaneseStudyApplication::class);
    }

    public function interviewStudies()
    {
        return $this->hasMany(InterviewStudy::class);
    }

    public function interviewStudyProgress()
    {
        return $this->hasOne(InterviewStudyProgress::class);
    }

    public function japaneseCourseCompletions()
    {
        return $this->hasMany(StudentJapaneseCourseCompletion::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
