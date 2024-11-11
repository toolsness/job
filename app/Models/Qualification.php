<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'qualification_name', 'qualification_category_id'
    ];

    public function qualificationCategory()
    {
        return $this->belongsTo(QualificationCategory::class);
    }

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'candidate_qualification');
    }
}
