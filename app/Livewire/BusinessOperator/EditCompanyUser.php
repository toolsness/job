<?php

namespace App\Livewire\BusinessOperator;

use App\Models\CompanyAdmin;
use App\Models\CompanyRepresentative;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Rules\EnglishName;
use App\Rules\JapaneseKatakanaName;
use App\Rules\JapanesePhoneNumber;
use App\Rules\ComplexPassword;
use App\Rules\PasswordConfirmation;

class EditCompanyUser extends Component
{
    use WithFileUploads;

    public User $user;

    public $name;

    public $nameKanji;

    public $nameKatakana;

    public $email;

    public $userType;

    public $contactPhoneNumber;

    public $companyName;

    public $companyIndustry;

    public $showConversionWarning = false;

    public $newUserType;

    public $isEditing = false;

    public $showDeleteConfirmation = false;

    public $tempImage;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:35', new EnglishName],
            'email' => 'required|email|max:255',
            'nameKanji' => 'required|string|max:255',
            'nameKatakana' => ['required', 'string', 'max:50', new JapaneseKatakanaName],
            'contactPhoneNumber' => ['required', 'string', 'max:20', new JapanesePhoneNumber],
            'tempImage' => 'nullable|image|max:1024',
        ];
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->userType = $this->user->user_type;

        if ($this->user->companyAdmin) {
            $this->nameKanji = $this->user->companyAdmin->name_kanji;
            $this->nameKatakana = $this->user->companyAdmin->name_katakana;
            $this->contactPhoneNumber = $this->user->companyAdmin->contact_phone_number;
            $this->companyName = $this->user->companyAdmin->company->name;
            $this->companyIndustry = $this->user->companyAdmin->company->industryType->name;
        } elseif ($this->user->companyRepresentative) {
            $this->nameKanji = $this->user->companyRepresentative->name_kanji;
            $this->nameKatakana = $this->user->companyRepresentative->name_katakana;
            $this->contactPhoneNumber = $this->user->companyRepresentative->contact_phone_number;
            $this->companyName = $this->user->companyRepresentative->company->name;
            $this->companyIndustry = $this->user->companyRepresentative->company->industryType->name;
        }
    }

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->loadUserData();
        $this->tempImage = null;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $this->user->name = $this->name;
            $this->user->email = $this->email;

            if ($this->tempImage) {
                if ($this->user->image) {
                    Storage::disk('s3')->delete($this->user->image);
                }
                $this->user->image = $this->tempImage->store('user-images', 's3');
            }

            $this->user->save();

            if ($this->user->user_type === 'CompanyAdmin') {
                $this->user->companyAdmin->update([
                    'name_kanji' => $this->nameKanji,
                    'name_katakana' => $this->nameKatakana,
                    'contact_phone_number' => $this->contactPhoneNumber,
                ]);
            } elseif ($this->user->user_type === 'CompanyRepresentative') {
                $this->user->companyRepresentative->update([
                    'name_kanji' => $this->nameKanji,
                    'name_katakana' => $this->nameKatakana,
                    'contact_phone_number' => $this->contactPhoneNumber,
                ]);
            }
        });

        $this->isEditing = false;
        $this->loadUserData();
        flash()->success('Company user updated successfully.');
    }

    public function deleteImage()
    {
        if ($this->user->image) {
            Storage::disk('s3')->delete($this->user->image);
            $this->user->image = null;
            $this->user->save();
        }
        $this->tempImage = null;
    }

    public function confirmDelete()
    {
        $this->showDeleteConfirmation = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
    }

    public function deleteUser()
    {
        if ($this->user->image) {
            Storage::disk('s3')->delete($this->user->image);
        }
        $this->user->delete();
        flash()->success('Company user deleted successfully.');

        return redirect()->route('business-operator.company-users');
    }

    public function confirmConversion($newType)
    {
        $this->newUserType = $newType;
        $this->showConversionWarning = true;
    }

    public function convertUserType()
    {
        DB::transaction(function () {
            if ($this->user->user_type === 'CompanyAdmin') {
                $companyId = $this->user->companyAdmin->company_id;
                $this->user->companyAdmin->delete();
                CompanyRepresentative::create([
                    'user_id' => $this->user->id,
                    'company_id' => $companyId,
                    'name_kanji' => $this->nameKanji,
                    'name_katakana' => $this->nameKatakana,
                    'contact_phone_number' => $this->contactPhoneNumber,
                ]);
                $this->user->user_type = 'CompanyRepresentative';
            } else {
                $companyId = $this->user->companyRepresentative->company_id;
                $this->user->companyRepresentative->delete();
                CompanyAdmin::create([
                    'user_id' => $this->user->id,
                    'company_id' => $companyId,
                    'name_kanji' => $this->nameKanji,
                    'name_katakana' => $this->nameKatakana,
                    'contact_phone_number' => $this->contactPhoneNumber,
                ]);
                $this->user->user_type = 'CompanyAdmin';
            }

            $this->user->save();
        });

        $this->showConversionWarning = false;
        $this->userType = $this->user->user_type;
        $this->mount($this->user->fresh());

        flash()->success('Company user converted successfully.');
    }

    public function render()
    {
        return view('livewire.business-operator.edit-company-user');
    }
}
