<?php

namespace App\Livewire\Student;

use App\Services\InterviewStudyProgressService;
use Livewire\Component;

class InterviewPreparationStudyPlan extends Component
{
    private function getStudyProgress()
    {
        $service = new InterviewStudyProgressService;

        return $service->getProgressData();
    }

    public function render()
    {
        $service = new InterviewStudyProgressService;
        $progressData = $service->getProgressData(false); // Changed to false to get full progress data
        $nextTask = $service->getNextTask();
        $previousTask = $service->getPreviousTask();
        $totalStudyTime = $service->getFormattedStudyTime('total');
        $practiceStudyTime = $service->getFormattedStudyTime('practice');
        $writingStudyTime = $service->getFormattedStudyTime('writing');

        // Ensure all tasks have a 'url' key
        $progressData = array_map(function ($task) {
            if (! isset($task['url'])) {
                $task['url'] = '#'; // Set a default URL if not present
            }

            return $task;
        }, $progressData);

        return view('livewire.student.interview-preparation-study-plan', [
            'progressData' => $progressData,
            'nextTask' => $nextTask,
            'previousTask' => $previousTask,
            'totalStudyTime' => $totalStudyTime,
            'practiceStudyTime' => $practiceStudyTime,
            'writingStudyTime' => $writingStudyTime,
        ]);
    }
}
