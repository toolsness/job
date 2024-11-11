<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacancyPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->user_type, ['BusinessOperator', 'CompanyRepresentative', 'CompanyAdmin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vacancy $vacancy): bool
    {
        if ($user->user_type === 'BusinessOperator') {
            return true;
        }

        if ($user->user_type === 'CompanyRepresentative' || $user->user_type === 'CompanyAdmin') {
            return $vacancy->company_id === $user->companyRepresentative?->company_id
                || $vacancy->company_id === $user->companyAdmin?->company_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->user_type, ['BusinessOperator', 'CompanyRepresentative', 'CompanyAdmin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vacancy $vacancy): bool
    {
        if ($user->user_type === 'BusinessOperator') {
            return true;
        }

        if ($user->user_type === 'CompanyRepresentative' || $user->user_type === 'CompanyAdmin') {
            return $vacancy->company_id === $user->companyRepresentative?->company_id
                || $vacancy->company_id === $user->companyAdmin?->company_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vacancy $vacancy): bool
    {
        if ($user->user_type === 'BusinessOperator') {
            return true;
        }

        if ($user->user_type === 'CompanyRepresentative' || $user->user_type === 'CompanyAdmin') {
            return $vacancy->company_id === $user->companyRepresentative?->company_id
                || $vacancy->company_id === $user->companyAdmin?->company_id;
        }

        return false;
    }

     /**
     * Determine whether the user can view job list search.
     */
    public function viewJobListSearch(User $user): bool
    {
        return in_array($user->user_type, ['CompanyRepresentative', 'CompanyAdmin', 'Student', 'Candidate']);
    }

    /**
     * Determine whether the user can view job details.
     */
    public function viewJobDetails(User $user, Vacancy $vacancy): bool
    {
        if (in_array($user->user_type, ['Student', 'Candidate'])) {
            return $vacancy->publish_category === 'Published';
        }

        if (in_array($user->user_type, ['CompanyRepresentative', 'CompanyAdmin'])) {
            return $vacancy->company_id === $user->companyRepresentative?->company_id
                || $vacancy->company_id === $user->companyAdmin?->company_id;
        }

        return $user->user_type === 'BusinessOperator';
    }

    public function scoutWith(User $user, ?Vacancy $vacancy = null)
{
    if (!in_array($user->user_type, ['CompanyAdmin', 'CompanyRepresentative'])) {
        return false;
    }

    if ($vacancy) {
        $companyId = $user->companyAdmin?->company_id ?? $user->companyRepresentative?->company_id;
        return $vacancy->company_id === $companyId;
    }

    return true;
}
}
