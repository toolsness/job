<?php

namespace App\Livewire\Company\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\CompanyRepresentative;
use Illuminate\Support\Facades\Mail;
use App\Mail\RepresentativeApproved;
use App\Mail\RepresentativeRejected;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ApproveRepresentatives extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';
    public $showConfirmModal = false;
    public $confirmAction = '';
    public $confirmUserId = null;
    public $confirmStatus = '';
    public $message = '';

    protected function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'filter' => 'required|in:all,Pending,Allowed,NotAllowed',
        ];
    }

    public function mount()
    {
        if (!Auth::user()->companyAdmin) {
            flash()->error('You do not have permission to view this page.');
            return redirect()->route('home');
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function confirmUpdateLoginAccess($userId, $status)
    {
        $this->confirmUserId = $userId;
        $this->confirmStatus = $status;
        $this->confirmAction = 'updateLoginAccess';
        $this->message = $status === 'Allowed' ? 'Are you sure you want to approve this representative?' : 'Are you sure you want to reject this representative?';
        $this->showConfirmModal = true;
    }

    public function confirmDeleteRepresentative($userId)
    {
        $this->confirmUserId = $userId;
        $this->confirmAction = 'deleteRepresentative';
        $this->message = 'Are you sure you want to delete this representative?';
        $this->showConfirmModal = true;
    }

    public function executeConfirmedAction()
    {
        if ($this->confirmAction === 'updateLoginAccess') {
            $this->updateLoginAccess($this->confirmUserId, $this->confirmStatus);
        } elseif ($this->confirmAction === 'deleteRepresentative') {
            $this->deleteRepresentative($this->confirmUserId);
        }
        $this->closeConfirmModal();
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
        $this->confirmAction = '';
        $this->confirmUserId = null;
        $this->confirmStatus = '';
        $this->message = '';
    }

    public function getRepresentativesProperty()
    {
        $companyAdmin = Auth::user()->companyAdmin;

        return User::where('user_type', 'CompanyRepresentative')
            ->whereHas('companyRepresentative', function ($query) use ($companyAdmin) {
                $query->where('company_id', $companyAdmin->company_id);
            })
            ->when($this->filter !== 'all', function ($query) {
                $query->where('login_permission_category', $this->filter);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->with('companyRepresentative')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function updateLoginAccess($userId, $status)
    {
        $user = $this->findAndValidateUser($userId);
        if (!$user) return;

        $user->login_permission_category = $status;
        $user->save();

        if ($status === 'Allowed') {
            Mail::to($user->email)->send(new RepresentativeApproved($user));
            $this->message = 'Representative approved successfully.';

            flash()->success('Representative approved successfully.');
        } elseif ($status === 'NotAllowed') {
            Mail::to($user->email)->send(new RepresentativeRejected($user));
            $this->message = 'Representative access revoked successfully.';

            flash()->success('Representative access revoked successfully.');
        }
        $this->showConfirmModal = true;
    }

    public function deleteRepresentative($userId)
    {
        $user = $this->findAndValidateUser($userId);
        if (!$user) return;

        $user->delete();
        $this->message = 'Representative deleted successfully.';
        $this->showConfirmModal = true;

        flash()->success('Representative deleted successfully.');
    }

    private function findAndValidateUser($userId)
    {
        $companyAdmin = Auth::user()->companyAdmin;
        $user = User::findOrFail($userId);

        if (!$user->companyRepresentative || $user->companyRepresentative->company_id !== $companyAdmin->company_id) {
            flash()->error('You do not have permission to manage this representative.');
            return null;
        }

        return $user;
    }

    public function render()
    {
        return view('livewire.company.admin.approve-representatives', [
            'representatives' => $this->representatives
        ]);
    }
}
