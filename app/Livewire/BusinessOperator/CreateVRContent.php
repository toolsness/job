<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\VRContent;
use App\Models\Company;
use App\Models\Vacancy;
use Livewire\WithFileUploads;

class CreateVRContent extends Component
{
    use WithFileUploads;

    public $content_name;
    public $content_category;
    public $content_link;
    public $image;
    public $remarks;
    public $company_id;
    public $status;

    protected $rules = [
        'content_name' => 'required|string|max:255',
        'content_category' => 'required|in:CompanyIntroduction,WorkplaceTour',
        'content_link' => 'required|url',
        'image' => 'nullable|image|max:1024',
        'remarks' => 'nullable|string',
        'company_id' => 'nullable|exists:companies,id',
        'status' => 'required|in:Public,Private,Draft',
    ];

    public function save()
    {
        $this->validate();

        $vrContent = VRContent::create($this->except('image'));

        if ($this->image) {
            $vrContent->image = $this->image->store('vr-content-images', 's3');
            $vrContent->save();
        }

        // session()->flash('message', 'VR Content created successfully.');
        flash()->success('VR Content created successfully.');
        return redirect()->route('business-operator.vr-contents.index');
    }

    public function render()
    {
        return view('livewire.business-operator.create-v-r-content', [
            'companies' => Company::orderBy('name')->get(),
            'vacancies' => Vacancy::orderBy('job_title')->get(),
        ]);
    }
}
