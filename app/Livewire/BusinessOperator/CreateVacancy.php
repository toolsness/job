<?php

namespace App\Livewire\BusinessOperator;

use App\Models\Company;
use App\Models\CompanyAdmin;
use App\Models\CompanyRepresentative;
use App\Models\Vacancy;
use App\Models\VacancyCategory;
use App\Models\VRContent;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateVacancy extends Component
{
    use WithFileUploads;

    public $company_id;

    public $user_type;

    public $selected_user_id;

    public $publish_category;

    public $image;

    public $vr_content_company_introduction_id;

    public $vr_content_workplace_tour_id;

    public $vacancy_category_id;

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

    public $selectedCompany;

    public $companyVRContents = [];

    public $companyUsers = [];

    protected $rules = [
        'company_id' => 'required|exists:companies,id',
        'user_type' => 'required|in:CompanyAdmin,CompanyRepresentative',
        'selected_user_id' => 'required|exists:users,id',
        'publish_category' => 'required|in:NotPublished,Published,PublicationStopped',
        'image' => 'nullable|image|max:1024',
        'vr_content_company_introduction_id' => 'nullable|exists:v_r_contents,id',
        'vr_content_workplace_tour_id' => 'nullable|exists:v_r_contents,id',
        'vacancy_category_id' => 'required|exists:vacancy_categories,id',
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
    ];

    public function updatedCompanyId($value)
    {
        $this->selectedCompany = Company::find($value);
        $this->companyVRContents = VRContent::where('company_id', $value)
            ->whereIn('status', ['Public', 'Private'])
            ->get();
        $this->user_type = null;
        $this->selected_user_id = null;
        $this->companyUsers = [];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedUserType($value)
    {
        if ($value === 'CompanyAdmin') {
            $this->companyUsers = CompanyAdmin::where('company_id', $this->company_id)->with('user')->get();
        } elseif ($value === 'CompanyRepresentative') {
            $this->companyUsers = CompanyRepresentative::where('company_id', $this->company_id)->with('user')->get();
        }
        $this->selected_user_id = null;
    }

    public function save()
    {
        $this->validate();

        $vacancyData = $this->except(['image', 'user_type', 'selected_user_id']);

        if ($this->user_type === 'CompanyAdmin') {
            $companyAdmin = CompanyAdmin::where('user_id', $this->selected_user_id)->first();
            $vacancyData['company_admin_id'] = $companyAdmin ? $companyAdmin->id : null;
        } elseif ($this->user_type === 'CompanyRepresentative') {
            $companyRepresentative = CompanyRepresentative::where('user_id', $this->selected_user_id)->first();
            $vacancyData['company_representative_id'] = $companyRepresentative ? $companyRepresentative->id : null;
        }

        $vacancy = Vacancy::create($vacancyData);

        if ($this->image) {
            $vacancy->image = $this->image->store('vacancy-images', 's3');
            $vacancy->save();
        }

        if ($this->vr_content_company_introduction_id) {
            VRContent::where('id', $this->vr_content_company_introduction_id)->update(['status' => 'Public']);
        }

        if ($this->vr_content_workplace_tour_id) {
            VRContent::where('id', $this->vr_content_workplace_tour_id)->update(['status' => 'Public']);
        }

        // session()->flash('message', 'Vacancy created successfully.');
        flash()->success('Vacancy created successfully.');

        return redirect()->route('business-operator.vacancies.index');
    }

    public function render()
    {
        return view('livewire.business-operator.create-vacancy', [
            'companies' => Company::orderBy('name')->get(),
            'vacancyCategories' => VacancyCategory::orderBy('name')->get(),
        ]);
    }
}
