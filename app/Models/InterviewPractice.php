<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewPractice extends Model
{
    protected $fillable = [
        'candidate_id',
        'total_score',
        'summary_feedback'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function options()
    {
        return $this->hasMany(InterviewPracticeOption::class);
    }

}
