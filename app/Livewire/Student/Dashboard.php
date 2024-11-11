<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Candidate;
use App\Services\InterviewStudyProgressService;

class Dashboard extends Component
{
    public function startJobHunting()
    {
        $user = Auth::user();

        if($user->user_type == 'Student') {
            $user->user_type = 'Candidate';
            $user->save();

            $candidate = $user->student->candidate;

            if(! $candidate) {
                $candidate = new Candidate();
                $candidate->student_id = $user->student->id;
                $candidate->publish_category = 'NotPublished';
            }

            $candidate->name = $user->name;
            $candidate->save();
        }

        return redirect()->route('home');
    }

    private function getStudyProgress()
    {
        $service = new InterviewStudyProgressService();
        return $service->getProgressData(forDashboard : true);
    }

    public function render()
    {
        $service = new InterviewStudyProgressService();
        $progressData = $service->getProgressData(true);
        $nextTask = $service->getNextTask();
        $previousTask = $service->getPreviousTask();
        $totalStudyTime = $service->getFormattedStudyTime('total');
        $practiceStudyTime = $service->getFormattedStudyTime('practice');
        $writingStudyTime = $service->getFormattedStudyTime('writing');

        return view('livewire.student.dashboard', [
            'progressData' => $progressData,
            'nextTask' => $nextTask,
            'previousTask' => $previousTask,
            'totalStudyTime' => $totalStudyTime,
            'practiceStudyTime' => $practiceStudyTime,
            'writingStudyTime' => $writingStudyTime,
        ]);
    }
}
