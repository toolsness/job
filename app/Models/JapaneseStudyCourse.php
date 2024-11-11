<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JapaneseStudyCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name', 'course_category', 'publish_category', 'monthly_amount'
    ];

    public function japaneseStudyApplications()
    {
        return $this->hasMany(JapaneseStudyApplication::class);
    }
}
