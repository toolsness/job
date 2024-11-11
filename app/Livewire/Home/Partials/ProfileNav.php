<?php

namespace App\Livewire\Home\Partials;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileNav extends Component
{
    public $userImage;

    protected $listeners = ['profileImageUpdated' => 'updateProfileImage'];

    public function mount()
    {
        $this->updateProfileImage();
    }

    public function updateProfileImage()
    {
        $user = Auth::user();

        if ($user->user_type === 'CompanyRepresentative' && $user->companyRepresentative?->company) {
            $this->userImage = $user->companyRepresentative->company->image
                ? Storage::url($user->companyRepresentative->company->image)
                : asset('placeholder.png');
        }
        elseif ($user->user_type === 'CompanyAdmin' && $user->companyAdmin?->company) {
            $this->userImage = $user->companyAdmin->company->image
                ? Storage::url($user->companyAdmin->company->image)
                : asset('placeholder.png');
        }
        else {
            $this->userImage = $user->image
                ? Storage::url($user->image)
                : asset('placeholder.png');
        }
    }

    public function render()
    {
        return view('livewire.home.partials.profile-nav');
    }
}
