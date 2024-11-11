<?php

namespace App\Livewire\BusinessOperator;

use App\Models\Company;
use App\Models\CompanyAdmin;
use App\Models\CompanyRepresentative;
use App\Models\Vacancy;
use App\Models\VacancyCategory;
use App\Models\VRContent;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditVacancy extends Component
{
    use WithFileUploads;

    public Vacancy $vacancy;

    public $company_id;

    public $user_type;

    public $selected_user_id;

    public $publish_category;

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

    public $image;

    public $tempImage;

    public $vr_content_company_introduction_id;

    public $vr_content_workplace_tour_id;

    public $vacancy_category_id;

    public $isEditing = false;

    public $showDeleteConfirmation = false;

    public $selectedCompany;

    public $companyVRContents = [];

    public $companyUsers = [];

    protected $rules = [
        'company_id' => 'required|exists:companies,id',
        'user_type' => 'required|in:CompanyAdmin,CompanyRepresentative',
        'selected_user_id' => 'required|exists:users,id',
        'publish_category' => 'required|in:NotPublished,Published,PublicationStopped',
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
        'tempImage' => 'nullable|image|max:1024',
        'vr_content_company_introduction_id' => 'nullable|exists:v_r_contents,id',
        'vr_content_workplace_tour_id' => 'nullable|exists:v_r_contents,id',
        'vacancy_category_id' => 'required|exists:vacancy_categories,id',
    ];

    public function mount(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
        $this->company_id = $vacancy->company_id;
        $this->selectedCompany = Company::find($vacancy->company_id);
        $this->loadVacancyData();
        $this->loadCompanyData();
    }

    public function loadVacancyData()
    {
        $this->fill($this->vacancy->toArray());

        // Determine user type and selected user
        if ($this->vacancy->company_admin_id) {
            $this->user_type = 'CompanyAdmin';
            $this->selected_user_id = $this->vacancy->companyAdmin->user_id;
        } elseif ($this->vacancy->company_representative_id) {
            $this->user_type = 'CompanyRepresentative';
            $this->selected_user_id = $this->vacancy->companyRepresentative->user_id;
        }

        // Load VR contents
        $this->companyVRContents = VRContent::where('company_id', $this->company_id)
            ->whereIn('status', ['Public', 'Private'])
            ->get();
    }

    public function loadCompanyData()
    {
        // Load company users based on the determined user type
        if ($this->user_type === 'CompanyAdmin') {
            $this->companyUsers = CompanyAdmin::where('company_id', $this->company_id)
                ->with('user')
                ->get();
        } elseif ($this->user_type === 'CompanyRepresentative') {
            $this->companyUsers = CompanyRepresentative::where('company_id', $this->company_id)
                ->with('user')
                ->get();
        }
    }

    public function updatedCompanyId($value)
    {
        $this->selectedCompany = Company::find($value);
        $this->companyVRContents = VRContent::where('company_id', $value)
            ->whereIn('status', ['Public', 'Private'])
            ->get();

        // Reset user selections when company changes
        $this->user_type = null;
        $this->selected_user_id = null;
        $this->companyUsers = [];
    }

    public function updatedUserType($value)
    {
        if ($value === 'CompanyAdmin') {
            $this->companyUsers = CompanyAdmin::where('company_id', $this->company_id)
                ->with('user')
                ->get();
        } elseif ($value === 'CompanyRepresentative') {
            $this->companyUsers = CompanyRepresentative::where('company_id', $this->company_id)
                ->with('user')
                ->get();
        }

        // Only reset selected user if changing user type
        if ($this->selected_user_id) {
            $userExists = $this->companyUsers->contains('user_id', $this->selected_user_id);
            if (!$userExists) {
                $this->selected_user_id = null;
            }
        }
    }

    public function save()
    {
        $this->validate();

        $vacancyData = $this->except(['tempImage', 'user_type', 'selected_user_id']);

        if ($this->user_type === 'CompanyAdmin') {
            $companyAdmin = CompanyAdmin::where('user_id', $this->selected_user_id)->first();
            $vacancyData['company_admin_id'] = $companyAdmin ? $companyAdmin->id : null;
            $vacancyData['company_representative_id'] = null;
        } elseif ($this->user_type === 'CompanyRepresentative') {
            $companyRepresentative = CompanyRepresentative::where('user_id', $this->selected_user_id)->first();
            $vacancyData['company_representative_id'] = $companyRepresentative ? $companyRepresentative->id : null;
            $vacancyData['company_admin_id'] = null;
        }

        $this->vacancy->update($vacancyData);

        if ($this->tempImage) {
            if ($this->vacancy->image) {
                Storage::disk('s3')->delete($this->vacancy->image);
            }
            $this->vacancy->image = $this->tempImage->store('vacancy-images', 's3');
            $this->vacancy->save();
        }

        if ($this->vr_content_company_introduction_id) {
            VRContent::where('id', $this->vr_content_company_introduction_id)->update(['status' => 'Public']);
        }

        if ($this->vr_content_workplace_tour_id) {
            VRContent::where('id', $this->vr_content_workplace_tour_id)->update(['status' => 'Public']);
        }

        $this->isEditing = false;
        $this->loadVacancyData();
        session()->flash('message', 'Vacancy updated successfully.');
    }

    public function deleteVacancy()
    {
        if ($this->vacancy->image) {
            Storage::disk('s3')->delete($this->vacancy->image);
        }

        if ($this->vacancy->vr_content_company_introduction_id) {
            VRContent::where('id', $this->vacancy->vr_content_company_introduction_id)->update(['status' => 'Private']);
        }

        if ($this->vacancy->vr_content_workplace_tour_id) {
            VRContent::where('id', $this->vacancy->vr_content_workplace_tour_id)->update(['status' => 'Private']);
        }

        $this->vacancy->delete();
        session()->flash('message', 'Vacancy deleted successfully.');

        return redirect()->route('business-operator.vacancies');
    }

    public function render()
    {
        return view('livewire.business-operator.edit-vacancy', [
            'companies' => Company::orderBy('name')->get(),
            'vacancyCategories' => VacancyCategory::orderBy('name')->get(),
        ]);
    }
}
