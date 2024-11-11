<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\Vacancy;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class InterviewPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view the interview confirmation.
     */
    public function viewConfirmation(User $user, Interview $interview): bool
    {
        // Business Operators can view all interviews
        if ($user->user_type === 'BusinessOperator') {
            return true;
        }

        // Students and Candidates can view their own interviews
        if (in_array($user->user_type, ['Student', 'Candidate'])) {
            return $interview->candidate->student_id === $user->student->id;
        }

        // Company Representatives and Admins can view interviews for their company
        if (in_array($user->user_type, ['CompanyRepresentative', 'CompanyAdmin'])) {
            $companyId = $user->companyRepresentative?->company_id ?? $user->companyAdmin?->company_id;
            return $interview->vacancy->company_id === $companyId;
        }

        return false;
    }

    public function viewInterviewApplicationTimeSelection(User $user, $vacancyId = null, $interviewId = null)
    {
        if (!in_array($user->user_type, ['Student', 'Candidate'])) {
            return false;
        }

        if ($vacancyId) {
            $vacancy = Vacancy::find($vacancyId);
            if ($vacancy && $vacancy->publish_category !== 'Published') {
                return false;
            }
        }

        if ($interviewId) {
            $interview = Interview::find($interviewId);
            if ($interview && $interview->candidate->student_id !== $user->student->id) {
                return false;
            }
        }

        return true;
    }
}
