<?php

namespace App\Livewire\Company;

use App\Rules\ComplexPassword;
use App\Rules\EnglishName;
use App\Rules\JapaneseKanjiName;
use App\Rules\JapaneseKatakanaName;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProfile extends Component
{
    use WithFileUploads;

    public $user;

    public $name;

    public $email;

    public $nameKanji;

    public $nameKatakana;

    public $contactPhoneNumber;

    public $newPassword;

    public $newPasswordConfirmation;

    public $isEditing = false;

    public $profileImage;

    public $tempProfileImage;

    public $userType;

    public $showPasswordConfirmation = false;

    public $currentPassword;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', new EnglishName],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'nameKanji' => ['required', 'string', 'max:255', new JapaneseKanjiName],
            'nameKatakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
            'contactPhoneNumber' => 'required|string|max:255',
            'newPassword' => ['nullable', new ComplexPassword],
            'newPasswordConfirmation' => ['nullable', 'same:newPassword'],
            'tempProfileImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024', // max 1MB
        ];
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->userType = $this->user->user_type;
        $this->loadUserData();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function loadUserData()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->profileImage = $this->user->image;

        if ($this->userType === 'CompanyRepresentative') {
            $companyUser = $this->user->companyRepresentative;
        } elseif ($this->userType === 'CompanyAdmin') {
            $companyUser = $this->user->companyAdmin;
        }

        $this->nameKanji = $companyUser->name_kanji;
        $this->nameKatakana = $companyUser->name_katakana;
        $this->contactPhoneNumber = $companyUser->contact_phone_number;
    }

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->loadUserData();
        $this->resetErrorBag();
        $this->tempProfileImage = null;
        $this->newPassword = null;
        $this->newPasswordConfirmation = null;
    }

    protected function resetForm()
    {
        $this->loadUserData();
        $this->resetErrorBag();
        $this->reset([
            'isEditing',
            'tempProfileImage',
            'currentPassword',
            'newPassword',
            'newPasswordConfirmation',
        ]);
    }

    public function updatedTempProfileImage()
    {
        $this->validateOnly('tempProfileImage');
    }

    public function confirmUpdate()
    {
        $this->validate();
        $this->showPasswordConfirmation = true;
    }

    public function updateProfile()
    {
        if (! $this->validateCurrentPassword()) {
            return;
        }

        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->userType === 'CompanyRepresentative') {
            $this->user->companyRepresentative->update(
                $this->loadCompanyUserData()
            );
        } elseif ($this->userType === 'CompanyAdmin') {
            $this->user->companyAdmin->update(
                $this->loadCompanyUserData()
            );
        }

        if ($this->newPassword) {
            $this->validate([
                'newPassword' => ['required', new ComplexPassword],
                'newPasswordConfirmation' => ['required', 'same:newPassword'],
            ]);

            $this->user->update([
                'password' => Hash::make($this->newPassword),
            ]);
        }

        if ($this->tempProfileImage) {
            $this->updateProfilePicture();
        }

        $this->resetForm();
        $this->showPasswordConfirmation = false;
        flash()->success('Profile updated successfully.');
    }

    private function validateCurrentPassword()
    {
        if (! Hash::check($this->currentPassword, $this->user->password)) {
            $this->addError('currentPassword', 'The current password is incorrect.');

            return false;
        }

        return true;
    }

    private function loadCompanyUserData()
    {
        return [
            'name_kanji' => $this->nameKanji,
            'name_katakana' => $this->nameKatakana,
            'contact_phone_number' => $this->contactPhoneNumber,
        ];
    }

    private function updateProfilePicture()
    {
        $imagePath = $this->tempProfileImage->store('profile-images', 's3');

        if ($this->user->image) {
            Storage::disk('s3')->delete($this->user->image);
        }

        $this->user->update(['image' => $imagePath]);
    }

    public function cancelPasswordConfirmation()
    {
        $this->showPasswordConfirmation = false;
        $this->currentPassword = '';
    }

    public function render()
    {
        return view('livewire.company.edit-profile');
    }
}
