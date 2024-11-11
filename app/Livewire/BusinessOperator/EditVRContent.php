<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\VRContent;
use App\Models\Company;
use App\Models\Vacancy;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditVRContent extends Component
{
    use WithFileUploads;

    public VRContent $vrContent;
    public $content_name;
    public $content_category;
    public $content_link;
    public $image;
    public $remarks;
    public $company_id;
    public $status;
    public $tempImage;
    public $isEditing = false;
    public $showDeleteConfirmation = false;

    protected $rules = [
        'content_name' => 'required|string|max:255',
        'content_category' => 'required|in:CompanyIntroduction,WorkplaceTour',
        'content_link' => 'required|url',
        'tempImage' => 'nullable|image|max:1024',
        'remarks' => 'nullable|string',
        'company_id' => 'nullable|exists:companies,id',
        'status' => 'required|in:Public,Private,Draft',
    ];

    public function mount(VRContent $vrContent)
    {
        $this->vrContent = $vrContent;
        $this->loadVRContentData();
    }

    public function loadVRContentData()
    {
        $this->content_name = $this->vrContent->content_name;
        $this->content_category = $this->vrContent->content_category;
        $this->content_link = $this->vrContent->content_link;
        $this->image = $this->vrContent->image;
        $this->remarks = $this->vrContent->remarks;
        $this->company_id = $this->vrContent->company_id;
        $this->status = $this->vrContent->status;
    }

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->loadVRContentData();
        $this->tempImage = null;
    }

    public function save()
    {
        $this->validate();

        $this->vrContent->update($this->except('tempImage'));

        if ($this->tempImage) {
            if ($this->vrContent->image) {
                Storage::disk('s3')->delete($this->vrContent->image);
            }
            $this->vrContent->image = $this->tempImage->store('vr-content-images', 's3');
            $this->vrContent->save();
        }

        $this->isEditing = false;
        $this->loadVRContentData();
        session()->flash('message', 'VR Content updated successfully.');
    }

    public function deleteImage()
    {
        if ($this->vrContent->image) {
            Storage::disk('s3')->delete($this->vrContent->image);
            $this->vrContent->image = null;
            $this->vrContent->save();
            $this->image = null;
        }
    }

    public function confirmDelete()
    {
        $this->showDeleteConfirmation = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
    }

    public function deleteVRContent()
    {
        if ($this->vrContent->image) {
            Storage::disk('s3')->delete($this->vrContent->image);
        }
        $this->vrContent->delete();
        session()->flash('message', 'VR Content deleted successfully.');
        return redirect()->route('business-operator.vr-contents.index');
    }

    public function render()
    {
        return view('livewire.business-operator.edit-v-r-content', [
            'companies' => Company::orderBy('name')->get(),
            'vacancies' => Vacancy::orderBy('job_title')->get(),
        ]);
    }
}
