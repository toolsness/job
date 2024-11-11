<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfPresentationHistory extends Model
{
    protected $fillable = [
        'candidate_id',
        'user_text',
        'ai_generated_text',
        'saved_text',
        'generation_token_usage'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
