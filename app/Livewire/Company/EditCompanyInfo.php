<?php

namespace App\Livewire\Company;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Rules\EnglishName;
use App\Rules\ComplexPassword;
use App\Rules\JapaneseKanjiName;
use App\Rules\JapaneseKatakanaName;

class EditCompanyInfo extends Component
{
    use WithFileUploads;

    public $user;
    public $company;
    public $companyUser;
    public $isEditing = false;
    public $showPasswordConfirmation = false;

    public $name;
    public $nameKanji;
    public $nameKatakana;
    public $address;
    public $website;
    public $contactEmail;
    public $contactPhone;
    public $companyUserNameKanji;
    public $companyUserNameKatakana;
    public $companyUserContactPhone;

    public $logo;
    public $tempLogo;
    public $userType;

    public $newPassword;
    public $newPasswordConfirmation;
    public $currentPassword;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nameKanji' => ['required', 'string', 'max:255', new JapaneseKanjiName],
            'nameKatakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
            'address' => 'required|string|max:255',
            'website' => 'required|url|max:255',
            'contactEmail' => ['required', 'email:rfc,dns', 'max:255'],
            'contactPhone' => 'required|string|max:255',
            'newPassword' => ['nullable', new ComplexPassword],
            'newPasswordConfirmation' => ['nullable', 'same:newPassword'],
            'companyUserNameKanji' => ['required', 'string', 'max:255', new JapaneseKanjiName],
            'companyUserNameKatakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
            'companyUserContactPhone' => 'required|string|max:255',
            'tempLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024', // max 1MB
        ];
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->userType = $this->user->user_type;

        if ($this->userType === 'CompanyRepresentative') {
            $this->companyUser = $this->user->companyRepresentative;
        } elseif ($this->userType === 'CompanyAdmin') {
            $this->companyUser = $this->user->companyAdmin;
        }

        $this->company = $this->companyUser->company;
        $this->loadCompanyData();
    }

    public function loadCompanyData()
    {
        $this->name = $this->company->name;
        $this->nameKanji = $this->company->name_kanji;
        $this->nameKatakana = $this->company->name_katakana;
        $this->address = $this->company->address;
        $this->website = $this->company->website;
        $this->contactEmail = $this->company->contact_email;
        $this->contactPhone = $this->company->contact_phone;
        $this->logo = $this->company->image;
        $this->companyUserNameKanji = $this->companyUser->name_kanji;
        $this->companyUserNameKatakana = $this->companyUser->name_katakana;
        $this->companyUserContactPhone = $this->companyUser->contact_phone_number;
    }

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->loadCompanyData();
        $this->resetErrorBag();
        $this->reset([
            'isEditing',
            'tempLogo',
            'currentPassword',
            'newPassword',
            'newPasswordConfirmation'
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedTempLogo()
    {
        $this->validateOnly('tempLogo');
    }

    public function confirmUpdate()
    {
        $this->validate();
        $this->showPasswordConfirmation = true;
    }

    public function updateCompanyInfo()
    {
        if (!$this->validateCurrentPassword()) {
            return;
        }

        $this->updateCompanyUserData();
        $this->updateCompanyData();

        if ($this->newPassword) {
            $this->updateUserPassword();
        }

        if ($this->tempLogo) {
            $this->updateCompanyLogo();
        }

        $this->resetForm();
        $this->showPasswordConfirmation = false;
        flash()->success('Company information updated successfully.');

        // update profile image on nav - live
        $this->dispatch('profileImageUpdated');
    }

    protected function validateCurrentPassword()
    {
        if (!Hash::check($this->currentPassword, $this->user->password)) {
            $this->addError('currentPassword', 'The current password is incorrect.');
            return false;
        }
        return true;
    }

    protected function updateCompanyUserData()
    {
        $this->companyUser->update([
            'name_kanji' => $this->companyUserNameKanji,
            'name_katakana' => $this->companyUserNameKatakana,
            'contact_phone_number' => $this->companyUserContactPhone
        ]);
    }

    protected function updateCompanyData()
    {
        $this->company->update([
            'name' => $this->name,
            'name_kanji' => $this->nameKanji,
            'name_katakana' => $this->nameKatakana,
            'address' => $this->address,
            'website' => $this->website,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
        ]);
    }

    protected function updateUserPassword()
    {
        $this->user->update([
            'password' => Hash::make($this->newPassword)
        ]);
    }

    protected function updateCompanyLogo()
    {
        $logoPath = $this->tempLogo->store('company-logos', 's3');

        if ($this->company->image) {
            Storage::disk('s3')->delete($this->company->image);
        }

        $this->company->update(['image' => $logoPath]);
    }

    public function cancelPasswordConfirmation()
    {
        $this->showPasswordConfirmation = false;
        $this->currentPassword = '';
    }

    public function render()
    {
        return view('livewire.company.edit-company-info');
    }
}
