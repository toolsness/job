<?php

namespace App\Livewire\Student;

use App\Models\Candidate;
use App\Models\Country;
use App\Models\Qualification;
use App\Models\QualificationCategory;
use App\Models\VacancyCategory;
use App\Services\InterviewStudyProgressService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;

class ProfileCvCreation extends Component
{
    public $name;

    public $gender;

    public $birth_date;

    public $age;

    public $nationality;

    public $college;

    public $countries;

    public $qualifications;

    public $last_education;

    public $work_history;

    public $qualification;

    public $japanese_language;

    public $desired_job_type;

    public $jobTypes;

    public $other_request;

    public $publish_category;

    public $qualificationCategories;

    public $selectedCategory = null;

    public $qualificationsByCategory = [];

    public $selectedQualifications = [];

    public $newQualification;

    protected $rules = [
        'name' => 'required|string|max:55',
        'gender' => 'required|string',
        'birth_date' => 'required|date',
        'nationality' => 'required|exists:countries,id',
        'college' => 'nullable|string|max:255',
        'last_education' => 'nullable|string',
        'work_history' => 'nullable|string',
        'qualification' => 'nullable|exists:qualifications,id',
        'japanese_language' => 'required|string',
        'desired_job_type' => 'required|exists:vacancy_categories,id',
        'other_request' => 'nullable|string|max:255',
        'publish_category' => 'required|in:Published,NotPublished,PublicationStopped',
        'selectedQualifications' => 'array',
        'selectedQualifications.*' => 'exists:qualifications,id',
    ];

    public function mount()
    {
        $this->countries = Country::orderBy('country_name')->get();
        $this->qualifications = Qualification::orderBy('qualification_name')->get();
        $this->jobTypes = VacancyCategory::all();
        $this->qualificationCategories = QualificationCategory::orderBy('name')->get();
        $this->updateQualificationsByCategory();
        $this->loadProfileData();
        $this->calculateAge();
    }

    private function loadProfileData()
    {
        $user = Auth::user();
        $candidate = $user->student->candidate;
        $this->name = $user->name;

        if ($candidate) {
            $this->gender = $candidate->gender;
            $this->birth_date = $candidate->birth_date ? $candidate->birth_date->format('Y-m-d') : null;
            $this->age = $candidate->birth_date ? date_diff($candidate->birth_date, today())->format('%y years') : null;
            $this->nationality = $candidate->nationality;
            $this->college = $candidate->college;
            $this->last_education = $candidate->last_education;
            $this->work_history = $candidate->work_history;
            $this->qualification = $candidate->qualification;
            $this->japanese_language = $candidate->japanese_language_qualification;
            $this->desired_job_type = $candidate->desired_job_type;
            $this->other_request = $candidate->other_request;
            $this->publish_category = $candidate->publish_category ?? 'NotPublished';
            $this->selectedQualifications = $candidate->qualifications->pluck('id')->toArray();
        }
    }

    public function updatedBirthDate()
    {
        $this->calculateAge();
    }

    private function calculateAge()
    {
        if ($this->birth_date) {
            $birthDate = Carbon::parse($this->birth_date);
            $this->age = $birthDate->age;
        } else {
            $this->age = null;
        }
    }

    public function updatedSelectedCategory($value)
    {
        $this->updateQualificationsByCategory();
    }

    private function updateQualificationsByCategory()
    {
        if ($this->selectedCategory) {
            $this->qualificationsByCategory = Qualification::where('qualification_category_id', $this->selectedCategory)
                ->orderBy('qualification_name')
                ->get();
        } else {
            $this->qualificationsByCategory = collect();
        }
    }

    public function removeQualification($index)
    {
        unset($this->selectedQualifications[$index]);
        $this->selectedQualifications = array_values($this->selectedQualifications);
    }

    public function addQualification()
    {
        if ($this->newQualification && ! in_array($this->newQualification, $this->selectedQualifications)) {
            $this->selectedQualifications[] = $this->newQualification;
            $this->newQualification = '';
            $this->dispatch('qualificationAdded');
        }
        $this->selectedCategory = null;
        $this->updateQualificationsByCategory();
        $this->dispatch('updatedQualifications');
    }

    public function save()
    {
        $this->validate();
        DB::beginTransaction();

        try {
            $service = new InterviewStudyProgressService;
            $user = Auth::user();
            $user->name = $this->name;
            $user->save();

            $candidate = Candidate::updateOrCreate(
                [
                    'student_id' => Auth::user()->student->id,
                ],
                [
                    'name' => $this->name, // Add this line
                    'gender' => $this->gender,
                    'birth_date' => $this->birth_date,
                    'nationality' => $this->nationality,
                    'college' => $this->college,
                    'last_education' => $this->last_education,
                    'work_history' => $this->work_history,
                    'qualification' => $this->qualification,
                    'japanese_language_qualification' => $this->japanese_language,
                    'desired_job_type' => $this->desired_job_type,
                    'other_request' => $this->other_request,
                    'publish_category' => $this->publish_category,
                ]
            );

            $candidate->qualifications()->sync($this->selectedQualifications);

            $service->updateProfileRegistrationProgress();
            DB::commit();

            flash()->success('CV profile updated successfully.');

            return redirect()->route('cv.creation.self-promotion');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating CV profile: '.$e->getMessage());

            flash()->error('An error occurred while updating the profile. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.student.profile-cv-creation');
    }
}
