<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Company;
use App\Models\IndustryType;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Rules\EnglishName;
use App\Rules\JapaneseName;
use App\Rules\JapaneseKatakanaName;
use App\Rules\JapanesePhoneNumber;

class EditCompany extends Component
{
    use WithFileUploads;

    public $company;
    public $industryTypes;
    public $isEditing = false;
    public $showDeleteConfirmation = false;
    public $newImage;

    protected function rules()
    {
        return [
            'company.name' => ['required', 'string', 'max:255', new EnglishName],
            'company.name_kanji' => ['required', 'string', 'max:255'],
            'company.name_katakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
            'company.industry_type_id' => 'required|exists:industry_types,id',
            'company.address' => 'required|string|max:255',
            'company.website' => 'required|url|max:255',
            'company.contact_email' => 'required|email|max:255',
            'company.contact_phone' => ['required', 'string', 'max:20', new JapanesePhoneNumber],
            'newImage' => 'nullable|image|max:1024', // 1MB Max
        ];
    }

    public function mount(Company $company)
    {
        $this->company = $company;
        $this->industryTypes = IndustryType::all();
    }

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->newImage = null;
        $this->company->refresh();
    }

    public function save()
    {
        $this->validate();

        if ($this->newImage) {
            if ($this->company->image) {
                Storage::disk('s3')->delete($this->company->image);
            }
            $this->company->image = $this->newImage->store('company-images', 's3');
        }

        $this->company->save();
        $this->isEditing = false;
        $this->newImage = null;

        flash()->success('Company updated successfully.');
    }

    public function confirmDelete()
    {
        $this->showDeleteConfirmation = true;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function deleteCompany()
    {
        if ($this->company->image) {
            Storage::disk('s3')->delete($this->company->image);
        }
        $this->company->delete();
        flash()->success('Company deleted successfully.');
        return redirect()->route('business-operator.companies');
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
    }

    public function deleteImage()
    {
        if ($this->company->image) {
            Storage::disk('s3')->delete($this->company->image);
            $this->company->image = null;
            $this->company->save();
        }
        $this->newImage = null;
    }

    public function render()
    {
        return view('livewire.business-operator.edit-company');
    }
}
