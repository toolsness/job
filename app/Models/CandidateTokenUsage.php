<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateTokenUsage extends Model
{
    protected $fillable = [
        'candidate_id',
        'daily_token_usage',
        'weekly_token_usage',
        'monthly_token_usage',
        'last_reset_date'
    ];

    protected $dates = ['last_reset_date'];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function incrementUsage($tokens)
    {
        $this->daily_token_usage += $tokens;
        $this->weekly_token_usage += $tokens;
        $this->monthly_token_usage += $tokens;
        $this->save();
        $this->checkAndResetUsage();
    }

    private function checkAndResetUsage()
    {
        $now = now();
        if ($now->diffInDays($this->last_reset_date) >= 1) {
            $this->daily_token_usage = 0;
        }
        if ($now->diffInWeeks($this->last_reset_date) >= 1) {
            $this->weekly_token_usage = 0;
        }
        if ($now->diffInMonths($this->last_reset_date) >= 1) {
            $this->monthly_token_usage = 0;
        }
        if ($this->isDirty()) {
            $this->last_reset_date = $now;
            $this->save();
        }
    }
}
