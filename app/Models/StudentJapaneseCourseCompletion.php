<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentJapaneseCourseCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'completed_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function japaneseStudyCourse()
    {
        return $this->belongsTo(JapaneseStudyCourse::class);
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
