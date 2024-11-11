<?php

namespace App\Livewire\Common;

use Livewire\Component;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SimplifiedJobInformationDetails extends Component
{
    public $vacancy;
    public $companyName;
    public $userType;
    public $showModal = false;
    public $modalMessage = '';

    public function mount($id)
    {
        $this->vacancy = Vacancy::with(['companyAdmin.company', 'companyRepresentative.company', 'vacancyCategory'])->findOrFail($id);
        $this->companyName = $this->getCompanyName();
        $this->userType = Auth::user()->user_type;
    }

    protected function getCompanyName()
    {
        if ($this->vacancy->companyAdmin) {
            return $this->vacancy->companyAdmin->company->name;
        } elseif ($this->vacancy->companyRepresentative) {
            return $this->vacancy->companyRepresentative->company->name;
        }
        return 'N/A';
    }

    public function showPopup($message)
    {
        $this->modalMessage = $message;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->modalMessage = '';
    }

    public function getImageUrl($path)
    {
        if (!$path) {
            return asset('placeholder2.png');
        }
        return Storage::url($path);
    }

    public function checkVrContentAvailability($contentType)
    {
        if ($contentType === 'Company Introduction') {
            return $this->vacancy->vrContentCompanyIntroduction()->exists() &&
                   $this->vacancy->vrContentCompanyIntroduction->status === 'Public';
        } elseif ($contentType === 'Workplace Tour') {
            return $this->vacancy->vrContentWorkplaceTour()->exists() &&
                   $this->vacancy->vrContentWorkplaceTour->status === 'Public';
        }
        return false;
    }

    public function handleVrContentClick($contentType)
    {
        $isAvailable = $this->checkVrContentAvailability($contentType);

        if ($isAvailable) {
            $contentTypeParam = $contentType === 'Company Introduction' ? 'CompanyIntroduction' : 'WorkplaceTour';
            return $this->redirect(route('job.vr-content', [
                'vacancyId' => $this->vacancy->id,
                'contentType' => $contentTypeParam
            ]));
        }

        if (in_array($this->userType, ['Student', 'Candidate'])) {
            $this->showPopup("$contentType not updated yet.");
        } elseif (in_array($this->userType, ['CompanyAdmin', 'CompanyRepresentative'])) {
            $this->showPopup("$contentType not updated yet. Please send a message to the Business Operator to add VR content.");
        }
    }

    public function render()
    {
        $hasCompanyIntroduction = $this->checkVrContentAvailability('Company Introduction');
        $hasWorkplaceTour = $this->checkVrContentAvailability('Workplace Tour');

        $imageUrl = $this->vacancy->image
            ? $this->getImageUrl($this->vacancy->image)
            : asset('placeholder2.png');

        return view('livewire.common.simplified-job-information-details', compact('imageUrl', 'hasCompanyIntroduction', 'hasWorkplaceTour'));
    }
}
