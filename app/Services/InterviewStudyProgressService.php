<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\InterviewStudyProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InterviewStudyProgressService
{
    private int $studentId;

    public function __construct(?int $studentId = null)
    {
        $this->studentId = $studentId ?? Auth::user()->student->id;
    }

    public function getProgressData(bool $forDashboard = false): array
    {
        $dataFields = $forDashboard ? $this->getProgressListForDashboard() : $this->getProgressListForStudyPlan();

        $interviewStudyProgress = InterviewStudyProgress::where('student_id', $this->studentId)->first();

        $finalProgress = $interviewStudyProgress
            ? $this->loadInterviewStudyProgress($dataFields, $interviewStudyProgress)
            : $this->loadZeroProgress($dataFields);

        return array_map(function ($item) {
            unset($item['column']);

            return $item;
        }, $finalProgress);

        if ($forDashboard) {
            return $this->loadDashboardProgress($finalProgress);
        }

        return $finalProgress;
    }

    private function loadDashboardProgress(array $progress): array
    {
        // Filter and format progress data for dashboard
        $dashboardProgress = array_filter($progress, function ($item) {
            return in_array($item['name'], ['Orientation', 'Profile Registration', 'Interview Response Practice', 'Mock Interview']);
        });

        // Add study hours
        $dashboardProgress[] = [
            'name' => 'Total Study Hours',
            'value' => $this->calculateTotalStudyHours(),
        ];

        return $dashboardProgress;
    }

    public function calculateTotalStudyHours(): string
    {
        $progress = InterviewStudyProgress::where('student_id', $this->studentId)->first();
        if (!$progress) {
            return '0:00:00';
        }

        $totalSeconds = $progress->getTotalStudyTime();
        return $this->formatTime($totalSeconds);
    }

    public function calculateStudyHoursByType($type): string
    {
        $progress = InterviewStudyProgress::where('student_id', $this->studentId)->first();
        if (!$progress) {
            return '0:00:00';
        }

        $totalSeconds = $progress->getStudyTimeByType($type);
        return $this->formatTime($totalSeconds);
    }

    public function trackStudyTime($seconds, $type): void
    {
        $progress = InterviewStudyProgress::firstOrCreate(['student_id' => $this->studentId]);
        $progress->addStudyTime($seconds, $type);
    }

    public function getFormattedStudyTime($type = 'total'): string
    {
        $progress = InterviewStudyProgress::where('student_id', $this->studentId)->first();
        if (!$progress) {
            return '0h 0m 0s';
        }

        $totalSeconds = $progress->getStudyTime($type);
        return $this->formatTime($totalSeconds);
    }

    private function formatTime($totalSeconds): string
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = $hours . 'h';
        }
        if ($minutes > 0 || $hours > 0) {
            $parts[] = $minutes . 'm';
        }
        $parts[] = $seconds . 's';

        return implode(' ', $parts);
    }


    public function getNextTask(): array
    {
        $progress = InterviewStudyProgress::where('student_id', $this->studentId)->first();
        if (! $progress) {
            return ['name' => 'Orientation', 'url' => route('interview-preparation-study-orientation')];
        }

        if ($progress->orientation_video_progress < 100) {
            return ['name' => 'Orientation', 'url' => route('interview-preparation-study-orientation')];
        }

        if ($progress->profile_registration_progress < 100) {
            return ['name' => 'CV Creation', 'url' => route('cv.creation.profile')];
        }

        if ($progress->interview_answer_creation_progress < 100) {
            return ['name' => 'Interview Answer Creation', 'url' => route('interview-answer.writing')];
        }

        if ($progress->interview_response_practice_progress < 100) {
            return ['name' => 'Interview Answer Practice', 'url' => route('interview-answer.practice')];
        }

        if ($progress->mock_interview_progress < 100) {
            return ['name' => 'Mock Interview', 'url' => route('home')];
        }

        return ['name' => 'Final Interview', 'url' => route('final-interview')];
    }

    public function getPreviousTask(): array
    {
        $progress = InterviewStudyProgress::where('student_id', $this->studentId)->first();
        if (! $progress) {
            return [];
        }

        $case = null;

        switch (true) {
            case $progress->mock_interview_progress == 100:
                $case = 'mock_interview';
                break;

            case $progress->interview_response_practice_progress == 100:
                $case = 'interview_response';
                break;

            case $progress->interview_answer_creation_progress == 100:
                $case = 'interview_answer_creation';
                break;

            case $progress->profile_registration_progress == 100:
                $case = 'profile_registration';
                break;

            case $progress->orientation_video_progress == 100:
                $case = 'orientation';
                break;
        }

        switch ($case) {
            case 'mock_interview':
                return ['name' => 'Mock Interview', 'url' => route('home')];

            case 'interview_response':
                return ['name' => 'Interview Answer Practice', 'url' => route('interview-answer.practice')];

            case 'interview_answer_creation':
                return ['name' => 'Interview Answer Creation', 'url' => route('interview-answer.writing')];

            case 'profile_registration':
                return ['name' => 'CV Creation', 'url' => route('cv.creation.profile')];

            case 'orientation':
                return ['name' => 'Orientation', 'url' => route('interview-preparation-study-orientation')];

            default:
                return [];
        }
    }


    private function loadInterviewStudyProgress(array $dataFields, InterviewStudyProgress $interviewStudyProgress): array
    {
        foreach ($dataFields as $key => $dataField) {

            $lastUpdateDateCol = $this->getLastUpdateDateColumn($dataField['column']);
            $lastUpdateDate = $interviewStudyProgress->{$lastUpdateDateCol};

            $dataFields[$key]['percentage'] = $interviewStudyProgress->{$dataField['column']};
            $dataFields[$key]['date'] = ($lastUpdateDate) ? date('Y/m/d', strtotime($lastUpdateDate)) : '--/--';
        }

        return $dataFields;
    }

    private function getLastUpdateDateColumn(string $progressColumn): string
    {
        // Replace 'progress' with 'date' to get the last_update_date column
        return str_replace('progress', 'date', $progressColumn);
    }

    private function loadZeroProgress(array $dataFields): array
    {
        foreach ($dataFields as $key => $dataField) {
            $dataFields[$key]['percentage'] = 0;
            $dataFields[$key]['date'] = '--/--';
        }

        return $dataFields;
    }

    private function getProgressListForDashboard(): array
    {
        return [
            ['name' => 'Orientation', 'column' => 'orientation_video_progress'],
            ['name' => 'Profile Registration', 'column' => 'profile_registration_progress'],
            ['name' => 'Interview Response Practice', 'column' => 'interview_response_practice_progress'],
            ['name' => 'Mock Interview', 'column' => 'mock_interview_progress'],
        ];
    }

    private function getProgressListForStudyPlan(): array
    {
        return [
            ['sl' => 1, 'name' => 'Orientation', 'column' => 'orientation_video_progress', 'url' => '/interview-preparation-study-orientation'],
            ['sl' => 2, 'name' => 'CV Creation', 'column' => 'profile_registration_progress', 'url' => '/cv-creation-profile'],
            ['sl' => 3, 'name' => 'Interview Answer Creation', 'column' => 'interview_answer_creation_progress', 'url' => '/interview-answer/writing'],
            ['sl' => 4, 'name' => 'Interview Answer Practice', 'column' => 'interview_response_practice_progress', 'url' => '/interview-answer/practice'],
            ['sl' => 5, 'name' => 'Mock Interview', 'column' => 'mock_interview_progress', 'url' => '#'], // Changed from empty string to '#'
            ['sl' => '★', 'name' => 'Final Interview', 'column' => 'final_interview_progress', 'url' => '#'], // Changed from empty string to '#'
        ];
    }

    public function updateOrientationProgress(): void
    {
        InterviewStudyProgress::updateOrCreate(
            ['student_id' => $this->studentId],
            ['orientation_video_progress' => 100, 'orientation_video_date' => date('Y-m-d')]
        );
    }

    public function updateProfileRegistrationProgress(): void
    {
        $totalProgress = $this->calculateProfileRegistrationProgress();

        InterviewStudyProgress::updateOrCreate(
            ['student_id' => $this->studentId],
            ['profile_registration_progress' => $totalProgress, 'profile_registration_date' => date('Y-m-d')]
        );
    }

    private function calculateProfileRegistrationProgress(): int
    {
        $totalProgress = 0;

        $profileFields = [
            'name' => 10,
            'gender' => 10,
            'birth_date' => 10,
            'nationality' => 10,
            'work_history' => 20,
            'qualifications' => 10,
            'college' => 10,
            'japanese_language_qualification' => 10,
            'desired_job_type' => 10,
        ];

        $profile = Candidate::where('student_id', $this->studentId)->first();

        if ($profile) {

            foreach ($profileFields as $key => $value) {

                if ($profile->{$key}) {
                    $totalProgress += $value;
                }
            }

            return $totalProgress;
        }

        return 0;
    }
}
