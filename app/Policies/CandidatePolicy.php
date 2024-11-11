<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CandidatePolicy
{
    use HandlesAuthorization;

    public function scout(User $user, ?Candidate $candidate = null)
    {
        return in_array($user->user_type, ['CompanyAdmin', 'CompanyRepresentative']);
    }

    public function viewCandidateDetails(User $user, $vacancyId = null, $interviewId = null)
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
