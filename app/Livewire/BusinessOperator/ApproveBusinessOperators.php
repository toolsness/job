<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\User;
use App\Models\BusinessOperator;
use Illuminate\Support\Facades\Mail;
use App\Mail\BusinessOperatorApproved;
use App\Mail\BusinessOperatorRejected;
use Illuminate\Support\Facades\Auth;

class ApproveBusinessOperators extends Component
{
    public $pendingBusinessOperators;

    public function mount()
    {
        $this->loadPendingBusinessOperators();
    }

    public function loadPendingBusinessOperators()
    {
        $this->pendingBusinessOperators = User::where('user_type', 'BusinessOperator')
            ->where('login_permission_category', 'Pending')
            ->with('businessOperator')
            ->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function approve($userId)
    {
        $user = $this->findAndValidateUser($userId);
        if (!$user) return;

        $user->login_permission_category = 'Allowed';
        $user->save();

        // Mail::to($user->email)->send(new BusinessOperatorApproved($user));

        $this->loadPendingBusinessOperators();
        flash()->success('Business Operator approved successfully.');
    }

    public function reject($userId)
    {
        $user = $this->findAndValidateUser($userId);
        if (!$user) return;

        $user->login_permission_category = 'NotAllowed';
        $user->save();

        // Mail::to($user->email)->send(new BusinessOperatorRejected($user));

        $this->loadPendingBusinessOperators();
        flash()->success('Business Operator rejected successfully.');
    }

    private function findAndValidateUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->user_type !== 'BusinessOperator') {
            flash()->error('Invalid user type.');
            return null;
        }

        return $user;
    }

    public function render()
    {
        return view('livewire.business-operator.approve-business-operators');
    }
}
