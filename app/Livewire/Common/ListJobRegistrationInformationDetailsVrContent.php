<?php

namespace App\Livewire\Common;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vacancy;
use App\Models\VRContent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ListJobRegistrationInformationDetailsVrContent extends Component
{
    use WithFileUploads;

    public $vacancy;
    public $vrContent;
    public $contentType;
    public $isEditing = false;
    public $newContentLink;
    public $newImage;
    public $newContentName;
    public $newRemarks;
    public $newStatus;

    public function mount($vacancyId, $contentType)
    {
        $this->vacancy = Vacancy::findOrFail($vacancyId);
        $this->contentType = $contentType;

        if ($contentType === 'CompanyIntroduction') {
            $this->vrContent = $this->vacancy->vrContentCompanyIntroduction;
        } elseif ($contentType === 'WorkplaceTour') {
            $this->vrContent = $this->vacancy->vrContentWorkplaceTour;
        }

        if (!$this->vrContent) {
            $this->vrContent = new VRContent([
                'content_name' => 'Default ' . $contentType . ' VR Content',
                'content_link' => 'https://example.com/default-vr-content',
                'image' => null,
                'content_category' => $contentType,
                'status' => 'Draft',
                'remarks' => '',
            ]);
        }

        if (!Gate::allows('viewVRContent', $this->vrContent)) {
            abort(403, 'Unauthorized action.');
        }

        $this->newContentLink = $this->vrContent->content_link;
        $this->newContentName = $this->vrContent->content_name;
        $this->newRemarks = $this->vrContent->remarks;
        $this->newStatus = $this->vrContent->status;
    }

    public function toggleEdit()
    {
        if (!Gate::allows('updateVRContent', $this->vrContent)) {
            abort(403, 'Unauthorized action.');
        }

        $this->isEditing = !$this->isEditing;
        if (!$this->isEditing) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->newContentLink = $this->vrContent->content_link;
        $this->newContentName = $this->vrContent->content_name;
        $this->newRemarks = $this->vrContent->remarks;
        $this->newStatus = $this->vrContent->status;
        $this->newImage = null;
    }

    public function save()
    {
        if (!Gate::allows('updateVRContent', $this->vrContent)) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate([
            'newContentLink' => 'required|url',
            'newContentName' => 'required|string|max:255',
            'newRemarks' => 'nullable|string',
            'newStatus' => 'required|in:Public,Private,Draft',
            'newImage' => 'nullable|image|max:1024', // 1MB Max
        ]);

        try {
            if (!$this->vrContent->id) {
                $this->vrContent = new VRContent();
                $this->vrContent->company_id = $this->vacancy->company_id;
                $this->vrContent->content_category = $this->contentType;
            }

            if ($this->newImage) {
                if ($this->vrContent->image) {
                    Storage::disk('s3')->delete($this->vrContent->image);
                }
                $imagePath = $this->newImage->store('vr-images', 's3');
                $this->vrContent->image = $imagePath;
            }

            $this->vrContent->content_link = $this->newContentLink;
            $this->vrContent->content_name = $this->newContentName;
            $this->vrContent->remarks = $this->newRemarks;
            $this->vrContent->status = $this->newStatus;
            $this->vrContent->save();

            if ($this->contentType === 'CompanyIntroduction') {
                $this->vacancy->vr_content_company_introduction_id = $this->vrContent->id;
            } else {
                $this->vacancy->vr_content_workplace_tour_id = $this->vrContent->id;
            }
            $this->vacancy->save();

            $this->isEditing = false;
            flash()->success('VR content updated successfully.');
        } catch (\Exception $e) {
            flash()->error('Error saving VR content: ' . $e->getMessage());
        }
    }

    public $showVRPopup = false;
    public $currentVRLink = '';

    public function playVR()
    {
        if (!Gate::allows('viewVRContent', $this->vrContent)) {
            abort(403, 'Unauthorized action.');
        }

        $this->currentVRLink = $this->vrContent->content_link;
        $this->showVRPopup = true;
    }

    public function render()
    {
        return view('livewire.common.list-job-registration-information-details-vr-content', [
            'vrLink' => $this->currentVRLink,
        ]);
    }
}
