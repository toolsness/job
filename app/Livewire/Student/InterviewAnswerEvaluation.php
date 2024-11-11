<?php

namespace App\Livewire\Student;

use App\Models\AiVoicePracticeResult;
use App\Models\InterviewPracticeOption;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class InterviewAnswerEvaluation extends Component
{
    use WithPagination;

    public $candidate;

    public $selectedPractice;

    public $selectedVoicePractice;

    public $showPracticeModal = false;

    public $showVoicePracticeModal = false;

    public $search = '';

    public $dateFrom;

    public $dateTo;

    public $scoreFilter = '';

    public $questionTypeFilter = '';

    public $practiceType = 'text'; // 'text' or 'voice'

    protected $queryString = ['search', 'dateFrom', 'dateTo', 'scoreFilter', 'questionTypeFilter', 'practiceType'];

    public function mount()
    {
        $this->candidate = Auth::user()->student->candidate;
        $this->practiceType = 'voice'; // Set default to voice practice
    }

    public function render()
    {
        $practices = $this->getPractices();
        $questionTypes = $this->getQuestionTypes();
        $passedModules = $this->getPassedModules();
        $voicePassedModules = $this->getVoicePassedModules();

        return view('livewire.student.interview-answer-evaluation', [
            'practices' => $practices,
            'questionTypes' => $questionTypes,
            'passedModules' => $passedModules,
            'voicePassedModules' => $voicePassedModules,
        ]);
    }

    private function getPractices()
    {
        if ($this->practiceType === 'voice') {
            return $this->getVoicePractices();
        }

        return $this->getTextPractices();
    }

    private function getTextPractices()
    {
        return $this->candidate->interviewPractices()
            ->with('options')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('options', function ($subQuery) {
                        $subQuery->where('user_text', 'like', '%'.$this->search.'%')
                            ->orWhere('question', 'like', '%'.$this->search.'%');
                    });
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->scoreFilter, function ($query) {
                $scoreRange = explode('-', $this->scoreFilter);
                $query->whereBetween('total_score', $scoreRange);
            })
            ->when($this->questionTypeFilter, function ($query) {
                $query->whereHas('options', function ($subQuery) {
                    $subQuery->where('option_type', $this->questionTypeFilter);
                });
            })
            ->latest()
            ->paginate(10);
    }

    private function getVoicePractices()
    {
        return AiVoicePracticeResult::whereHas('interviewWritingPractice', function ($query) {
            $query->where('candidate_id', $this->candidate->id);
        })
            ->with('interviewWritingPractice')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('transcribed_text', 'like', '%'.$this->search.'%')
                        ->orWhereHas('interviewWritingPractice', function ($subQuery) {
                            $subQuery->where('question', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->scoreFilter, function ($query) {
                $scoreRange = explode('-', $this->scoreFilter);
                $query->whereBetween('overall_score', $scoreRange);
            })
            ->when($this->questionTypeFilter, function ($query) {
                $query->whereHas('interviewWritingPractice', function ($subQuery) {
                    $subQuery->where('question_type', $this->questionTypeFilter);
                });
            })
            ->latest()
            ->paginate(10);
    }

    private function getQuestionTypes()
    {
        if ($this->practiceType === 'voice') {
            return AiVoicePracticeResult::whereHas('interviewWritingPractice', function ($query) {
                $query->where('candidate_id', $this->candidate->id);
            })
                ->with('interviewWritingPractice')
                ->get()
                ->pluck('interviewWritingPractice.question_type')
                ->unique()
                ->map(function ($type) {
                    return ['value' => $type, 'label' => ucfirst(str_replace('_', ' ', $type))];
                });
        }

        return InterviewPracticeOption::distinct('option_type')
            ->pluck('option_type')
            ->filter()
            ->map(function ($type) {
                return ['value' => $type, 'label' => ucfirst(str_replace('_', ' ', $type))];
            });
    }

    public function showPractice($id)
    {
        if ($this->practiceType === 'voice') {
            $this->selectedVoicePractice = AiVoicePracticeResult::with('interviewWritingPractice')->findOrFail($id);
            $this->showVoicePracticeModal = true;
        } else {
            $this->selectedPractice = $this->candidate->interviewPractices()->with('options')->findOrFail($id);
            $this->showPracticeModal = true;
        }
    }

    public function closePracticeModal()
    {
        $this->showPracticeModal = false;
        $this->showVoicePracticeModal = false;
        $this->selectedPractice = null;
        $this->selectedVoicePractice = null;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'scoreFilter', 'questionTypeFilter']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function getPassedModules()
    {
        $modules = [
            'self_introduction',
            'motivation',
            'advantages_disadvantages',
            'future_plans',
            'questions_to_companies',
        ];

        $passedModules = [];

        foreach ($modules as $module) {
            $highestScore = $this->candidate->interviewPractices()
                ->whereHas('options', function ($query) use ($module) {
                    $query->where('option_type', $module);
                })
                ->max('total_score');

            if ($highestScore >= 80) {
                $passedModules[$module] = $highestScore;
            }
        }

        return $passedModules;
    }

    private function getVoicePassedModules()
    {
        $modules = [
            'self_introduction',
            'motivation',
            'advantages_disadvantages',
            'future_plans',
            'questions_to_companies',
        ];

        $passedModules = [];

        foreach ($modules as $module) {
            $highestScore = AiVoicePracticeResult::whereHas('interviewWritingPractice', function ($query) use ($module) {
                $query->where('candidate_id', $this->candidate->id)
                    ->where('question_type', $module);
            })
                ->max('overall_score');

            if ($highestScore >= 80) {
                $passedModules[$module] = $highestScore;
            }
        }

        return $passedModules;
    }

    public function switchPracticeType($type)
    {
        $this->practiceType = $type;
        $this->resetPage();
    }
}
