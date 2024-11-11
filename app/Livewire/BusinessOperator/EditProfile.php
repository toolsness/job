<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Rules\ComplexPassword;
use App\Rules\EnglishName;
use App\Rules\JapaneseKanjiName;
use App\Rules\JapaneseKatakanaName;

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
    public $showPasswordConfirmation = false;
    public $currentPassword;
    public $isLoading = false;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:55', new EnglishName],
            'email' => ['required', 'email:rfc,dns', 'max:70'],
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

        $businessOperator = $this->user->businessOperator;
        $this->nameKanji = $businessOperator->name_kanji;
        $this->nameKatakana = $businessOperator->name_katakana;
        $this->contactPhoneNumber = $businessOperator->contact_phone_number;
    }

    public function startEditing()
    {
        $this->isLoading = true;
        $this->isEditing = true;
        $this->isLoading = false;
    }

    public function cancelEditing()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->loadUserData();
        $this->resetErrorBag();
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
            'newPasswordConfirmation'
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
    if (!$this->validateCurrentPassword()) {
        return;
    }

    $this->validate();

    $this->isLoading = true;

    $this->user->update([
        'name' => $this->name,
        'email' => $this->email,
    ]);

    $this->user->businessOperator->update([
        'name_kanji' => $this->nameKanji,
        'name_katakana' => $this->nameKatakana,
        'contact_phone_number' => $this->contactPhoneNumber
    ]);

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
    $this->isLoading = false;
    flash()->success('Profile updated successfully.');
}

    private function validateCurrentPassword()
    {
        if (!Hash::check($this->currentPassword, $this->user->password)) {
            $this->addError('currentPassword', 'The current password is incorrect.');
            return false;
        }
        return true;
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
        return view('livewire.business-operator.edit-profile');
    }
}
