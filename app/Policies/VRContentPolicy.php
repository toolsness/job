<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VRContent;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VRContentPolicy
{
    use HandlesAuthorization;

    public function viewVRContent(User $user, VRContent $vrContent = null)
    {
        if ($vrContent === null) {
            // If no specific VRContent is provided, we're checking general access
            return in_array($user->user_type, ['Student', 'Candidate', 'CompanyRepresentative', 'CompanyAdmin', 'BusinessOperator']);
        }

        if (in_array($user->user_type, ['Student', 'Candidate'])) {
            return $vrContent->status === 'Public';
        }

        if (in_array($user->user_type, ['CompanyRepresentative', 'CompanyAdmin'])) {
            return $vrContent->company_id === $user->companyRepresentative?->company_id
                || $vrContent->company_id === $user->companyAdmin?->company_id;
        }

        return $user->user_type === 'BusinessOperator';
    }

    public function updateVRContent(User $user, VRContent $vrContent = null)
    {
        if ($vrContent === null) {
            // If no specific VRContent is provided, we're checking general access
            return in_array($user->user_type, ['CompanyAdmin', 'CompanyRepresentative', 'BusinessOperator']);
        }

        if ($user->user_type === 'BusinessOperator') {
            return true;
        }

        if (in_array($user->user_type, ['CompanyAdmin', 'CompanyRepresentative'])) {
            return $vrContent->company_id === $user->companyAdmin?->company_id
                || $vrContent->company_id === $user->companyRepresentative?->company_id;
        }

        return false;
    }
}
