<?php

namespace App\Livewire\Common;

use App\Models\Vacancy;
use App\Models\VacancyCategory;
use App\Models\VRContent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateJobListing extends Component
{
    use WithFileUploads;

    public $companyName;

    public $job_title;

    public $monthly_salary;

    public $work_location;

    public $working_hours;

    public $transportation_expenses;

    public $overtime_pay;

    public $salary_increase_and_bonuses;

    public $social_insurance;

    public $japanese_language;

    public $other_details;

    public $publish_category = 'NotPublished';

    public $image;

    public $vacancy_category_id;

    public $company_id;

    public $isUploading = false;

    public $isSaving = false;

    public $vr_content_company_introduction_id;

    public $vr_content_workplace_tour_id;

    protected function rules()
    {
        return [
            'job_title' => 'required|string|max:255',
            'monthly_salary' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'working_hours' => 'required|string|max:255',
            'transportation_expenses' => 'required|string|max:255',
            'overtime_pay' => 'required|string|max:255',
            'salary_increase_and_bonuses' => 'required|string|max:255',
            'social_insurance' => 'required|string|max:255',
            'japanese_language' => 'required|string|max:255',
            'other_details' => 'nullable|string',
            'publish_category' => 'required|in:NotPublished,Published,PublicationStopped',
            'image' => 'nullable|image|max:1024',
            'vacancy_category_id' => 'required|exists:vacancy_categories,id',
            'vr_content_company_introduction_id' => 'nullable|exists:v_r_contents,id',
            'vr_content_workplace_tour_id' => 'nullable|exists:v_r_contents,id',
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type === 'CompanyAdmin') {
            $this->company_id = $user->companyAdmin->company_id;
            $this->companyName = $user->companyAdmin->company->name;
        } elseif ($user->user_type === 'CompanyRepresentative') {
            $this->company_id = $user->companyRepresentative->company_id;
            $this->companyName = $user->companyRepresentative->company->name;
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedImage()
    {
        $this->isUploading = true;
        $this->validate([
            'image' => 'image|max:1024',
        ]);
        $this->isUploading = false;
    }

    public $selectedVRContents = [
        'CompanyIntroduction' => null,
        'WorkplaceTour' => null,
    ];

    public function selectVRContent($contentType)
    {
        $vrContents = VRContent::where('company_id', $this->company_id)
            ->where('content_category', $contentType)
            ->whereIn('status', ['Public', 'Private'])
            ->get();
        $this->dispatch('openVRContentModal', $vrContents, $contentType);
    }

    public function setSelectedVRContent($contentType, $vrContentId)
    {
        if ($contentType === 'CompanyIntroduction') {
            $this->vr_content_company_introduction_id = $vrContentId;
        } elseif ($contentType === 'WorkplaceTour') {
            $this->vr_content_workplace_tour_id = $vrContentId;
        }
    }

    public function save()
    {
        $this->isSaving = true;
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('vacancy_images', 's3');
        }

        $vacancy = Vacancy::create([
            'company_id' => $this->company_id,
            'job_title' => $this->job_title,
            'monthly_salary' => $this->monthly_salary,
            'work_location' => $this->work_location,
            'working_hours' => $this->working_hours,
            'transportation_expenses' => $this->transportation_expenses,
            'overtime_pay' => $this->overtime_pay,
            'salary_increase_and_bonuses' => $this->salary_increase_and_bonuses,
            'social_insurance' => $this->social_insurance,
            'japanese_language' => $this->japanese_language,
            'other_details' => $this->other_details,
            'publish_category' => $this->publish_category,
            'image' => $imagePath,
            'vacancy_category_id' => $this->vacancy_category_id,
            'vr_content_company_introduction_id' => $this->vr_content_company_introduction_id ?: null,
            'vr_content_workplace_tour_id' => $this->vr_content_workplace_tour_id ?: null,
        ]);

        if (Auth::user()->user_type === 'CompanyAdmin') {
            $vacancy->company_admin_id = Auth::user()->companyAdmin->id;
        } elseif (Auth::user()->user_type === 'CompanyRepresentative') {
            $vacancy->company_representative_id = Auth::user()->companyRepresentative->id;
        }

        $vacancy->save();

        flash()->success('A New Vacancy Created Successfully!');
        $this->isSaving = false;

        return redirect()->route('job-list.search');
    }

    public function render()
    {
        return view('livewire.common.create-job-listing', [
            'vacancyCategories' => VacancyCategory::all(),
            'companyIntroductions' => VRContent::where('company_id', $this->company_id)
                ->where('content_category', 'CompanyIntroduction')
                ->whereIn('status', ['Public', 'Private'])
                ->get(),
            'workplaceTours' => VRContent::where('company_id', $this->company_id)
                ->where('content_category', 'WorkplaceTour')
                ->whereIn('status', ['Public', 'Private'])
                ->get(),
        ]);
    }
}
