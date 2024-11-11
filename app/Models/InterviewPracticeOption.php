<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewPracticeOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_practice_id',
        'option_type',
        'question',
        'user_text',
        'improved_answer',
        'overall_score',
        'content_score',
        'language_score',
        'structure_score',
        'overall_feedback',
        'errors',
        'practice_mode',
        'user_voice_url',
        'ai_voice_url',
        'generation_token_usage',
        'evaluation_token_usage',
        'selected_for_voice_practice',
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    public function interviewPractice()
    {
        return $this->belongsTo(InterviewPractice::class);
    }

    public function getTotalTokenUsage()
    {
        return $this->generation_token_usage + $this->evaluation_token_usage;
    }

    public function practice()
    {
        return $this->belongsTo(InterviewPractice::class, 'interview_practice_id');
    }

    public function getErrorsAttribute($value)
    {
        return is_string($value) ? json_decode($value, true) : $value;
    }

    public function aiVoicePracticeResults()
    {
        return $this->hasMany(AiVoicePracticeResult::class);
    }

    public static function selectForVoicePractice($optionId)
    {
        $option = self::findOrFail($optionId);
        $optionType = $option->option_type;

        // Set all options of the same type to false
        self::where('option_type', $optionType)->update(['selected_for_voice_practice' => false]);

        // Toggle the selected option
        $option->selected_for_voice_practice = !$option->selected_for_voice_practice;
        $option->save();

        // Ensure only 5 options are selected in total
        $selectedCount = self::where('selected_for_voice_practice', true)->count();
        if ($selectedCount > 5) {
            $oldestSelected = self::where('selected_for_voice_practice', true)
                ->orderBy('updated_at', 'asc')
                ->first();
            $oldestSelected->update(['selected_for_voice_practice' => false]);
        }
    }
}
