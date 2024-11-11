<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewWritingPractice extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'question_type',
        'question',
        'user_answer',
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
        'selected_for_voice_practice'
    ];

    protected $casts = [
        'errors' => 'array',
        'selected_for_voice_practice' => 'boolean',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function aiVoicePracticeResults()
    {
        return $this->hasMany(AiVoicePracticeResult::class);
    }

    public static function selectForVoicePractice($practiceId)
    {
        $practice = self::findOrFail($practiceId);
        $questionType = $practice->question_type;

        // Set all practices of the same type to false
        self::where('candidate_id', $practice->candidate_id)
            ->where('question_type', $questionType)
            ->update(['selected_for_voice_practice' => false]);

        // Toggle the selected practice
        $practice->selected_for_voice_practice = !$practice->selected_for_voice_practice;
        $practice->save();

        // Ensure only 5 practices are selected for voice practice in total
        $selectedCount = self::where('candidate_id', $practice->candidate_id)
            ->where('selected_for_voice_practice', true)
            ->count();

        if ($selectedCount > 5) {
            $oldestSelected = self::where('candidate_id', $practice->candidate_id)
                ->where('selected_for_voice_practice', true)
                ->orderBy('updated_at', 'asc')
                ->first();
            $oldestSelected->update(['selected_for_voice_practice' => false]);
        }
    }
}
