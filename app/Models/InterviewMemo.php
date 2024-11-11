<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewMemo extends Model
{
    use HasFactory;

    protected $fillable = ['interview_id', 'user_id', 'content'];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
