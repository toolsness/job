<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Company;
use App\Models\IndustryType;
use Livewire\WithFileUploads;
use App\Rules\EnglishName;
use App\Rules\JapaneseName;
use App\Rules\JapaneseKatakanaName;
use App\Rules\JapanesePhoneNumber;

class CreateCompany extends Component
{
    use WithFileUploads;

    public $name;
    public $name_kanji;
    public $name_katakana;
    public $industry_type_id;
    public $address;
    public $website;
    public $contact_email;
    public $contact_phone;
    public $image;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', new EnglishName],
            'name_kanji' => ['required', 'string', 'max:255'],
            'name_katakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
            'industry_type_id' => 'required|exists:industry_types,id',
            'address' => 'required|string|max:255',
            'website' => 'required|url|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => ['required', 'string', 'max:20', new JapanesePhoneNumber],
            'image' => 'nullable|image|max:1024', // 1MB Max
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        $data = $this->all();

        if ($this->image) {
            $data['image'] = $this->image->store('company-images', 's3');
        }

        Company::create($data);

        flash()->success('Company created successfully.');

        return redirect()->route('business-operator.companies');
    }

    public function deleteImage()
    {
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.business-operator.create-company', [
            'industryTypes' => IndustryType::all(),
        ]);
    }
}
