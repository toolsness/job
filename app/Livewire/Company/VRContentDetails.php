<?php

namespace App\Livewire\Company;

use App\Models\VRContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VRContentDetails extends Component
{
    use WithFileUploads;

    public $vrContent;
    public $isEditing = false;
    public $newContentName;
    public $newContentLink;
    public $newRemarks;
    public $newStatus;
    public $newImage;

    protected $rules = [
        'newContentName' => 'required|string|max:255',
        'newContentLink' => 'required|url',
        'newRemarks' => 'nullable|string',
        'newStatus' => 'required|in:Public,Private,Draft',
        'newImage' => 'nullable|image|max:1024',
    ];

    public function mount(VRContent $vrContent)
    {
        // Check if the authenticated user has permission to view this VR content
        $user = Auth::user();
        $userCompany = $user->companyAdmin?->company ?? $user->companyRepresentative?->company;

        if (!$userCompany || $userCompany->id !== $vrContent->company_id) {            // error code 403
            abort(403);
        }

        $this->vrContent = $vrContent;
        $this->loadVRContentData();
    }

    public function loadVRContentData()
    {
        $this->newContentName = $this->vrContent->content_name;
        $this->newContentLink = $this->vrContent->content_link;
        $this->newRemarks = $this->vrContent->remarks;
        $this->newStatus = $this->vrContent->status;
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
        if (!$this->isEditing) {
            $this->loadVRContentData();
            $this->newImage = null;
        }
    }

    public function save()
    {
        $this->validate();

        $this->vrContent->content_name = $this->newContentName;
        $this->vrContent->content_link = $this->newContentLink;
        $this->vrContent->remarks = $this->newRemarks;
        $this->vrContent->status = $this->newStatus;

        if ($this->newImage) {
            if ($this->vrContent->image) {
                Storage::disk('s3')->delete($this->vrContent->image);
            }
            $this->vrContent->image = $this->newImage->store('vr-images', 's3');
        }

        $this->vrContent->save();
        $this->isEditing = false;
        flash()->success('VR content updated successfully.');
    }

    public function render()
    {
        return view('livewire.company.v-r-content-details');
    }
}
