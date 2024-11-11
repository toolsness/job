<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JapaneseStudyApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'japanese_study_course_id', 'student_id', 'month_of_use', 'start_date',
        'estimated_end_date', 'monthly_amount', 'contract_date', 'credit_card_company',
        'credit_card_number', 'credit_card_expiry', 'credit_card_security_number'
    ];

    protected $casts = [
        'month_of_use' => 'date',
        'start_date' => 'date',
        'estimated_end_date' => 'date',
        'contract_date' => 'datetime',
    ];

    public function japaneseStudyCourse()
    {
        return $this->belongsTo(JapaneseStudyCourse::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
