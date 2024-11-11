<?php

namespace App\Livewire\Student;

use App\Services\InterviewStudyProgressService;
use Livewire\Component;

class Orientation extends Component
{
    public $hasUnderstoodInterviewPreparation = false;
    public $showModal = false;
    public $forwardingStep;

    protected $rules = [
        'hasUnderstoodInterviewPreparation' => 'required|boolean',
    ];

    public function mount()
    {
        $progress = auth()->user()->student->interviewStudyProgress;
        $this->hasUnderstoodInterviewPreparation = $progress && $progress->orientation_video_progress == 100;
    }

    public function updatedHasUnderstoodInterviewPreparation($value)
    {
        if ($value) {
            $this->updateOrientationProgress();
        }
    }

    private function updateOrientationProgress()
    {
        $service = new InterviewStudyProgressService;
        $service->updateOrientationProgress();
    }

    public function interviewPracticeRequest()
    {
        $this->validate();
        $this->showModal = true;
        $this->forwardingStep = 'Interview Practice';
    }

        public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function cvCreationRequest()
    {
        $this->validate();
        $this->showModal = true;
        $this->forwardingStep = 'CV';
    }

    public function cancelNextStep()
    {
        $this->showModal = false;
    }

    public function forwardNextStep()
    {
        $this->showModal = false;

        if ($this->forwardingStep == 'CV') {
            return redirect()->route('cv.creation.profile');
        }

        return redirect()->route('interview-preparation-study-plan');
    }

    public function render()
    {
        return view('livewire.student.orientation');
    }
}
