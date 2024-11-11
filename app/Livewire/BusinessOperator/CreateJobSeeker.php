<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Student;
use App\Models\Qualification;
use App\Models\QualificationCategory;
use App\Models\Country;
use App\Models\VacancyCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class CreateJobSeeker extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $name_kanji;
    public $name_katakana;
    public $name_japanese;
    public $contact_phone_number;
    public $gender;
    public $birth_date;
    public $nationality;
    public $countries;
    public $qualifications;
    public $last_education;
    public $work_history;
    public $self_presentation;
    public $personal_preference;
    public $profileImage;
    public $cvImage;
    public $desired_job_type;
    public $japanese_language_qualification;
    public $other_request;
    public $publish_category = 'NotPublished';
    public $selectedQualifications = [];
    public $qualificationCategories;
    public $selectedCategory = null;
    public $qualificationsByCategory = [];
    public $jobTypes;
    public $newQualification;
    public $selectedQualificationsDetails;

    public $username;

    protected $rules = [
        'name' => 'required|string|max:55',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'name_kanji' => 'nullable|string|max:255',
        'name_katakana' => 'nullable|string|max:255',
        'name_japanese' => 'nullable|string|max:255',
        'contact_phone_number' => 'nullable|string|max:255',
        'gender' => 'required|string',
        'birth_date' => 'required|date',
        'nationality' => 'required|exists:countries,id',
        'last_education' => 'nullable|string',
        'work_history' => 'nullable|string',
        'self_presentation' => 'nullable|string',
        'personal_preference' => 'nullable|string',
        'profileImage' => 'nullable|image|max:1024',
        'cvImage' => 'nullable|image|max:1024',
        'desired_job_type' => 'required|exists:vacancy_categories,id',
        'japanese_language_qualification' => 'required|string',
        'other_request' => 'nullable|string',
        'publish_category' => 'required|in:NotPublished,Published,PublicationStopped',
        'selectedQualifications' => 'array',
        'selectedQualifications.*' => 'exists:qualifications,id',
    ];

    public function mount()
    {
        $this->countries = Country::orderBy('country_name')->get();
        $this->qualifications = Qualification::orderBy('qualification_name')->get();
        $this->jobTypes = VacancyCategory::orderBy('name')->get();
        $this->qualificationCategories = QualificationCategory::orderBy('name')->get();
        $this->loadSelectedQualifications();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        if ($propertyName === 'name') {
            $this->generateUsername();
        }
    }

    public function generateUsername()
    {
        $date = now()->format('Ymd');
        $baseUsername = 'S' . $date;
        $suffix = 1;

        do {
            $username = $baseUsername . str_pad($suffix, 3, '0', STR_PAD_LEFT);
            $exists = User::where('username', $username)->exists();
            $suffix++;
        } while ($exists);

        $this->username = $username;
    }

private function loadSelectedQualifications()
{
    $this->selectedQualificationsDetails = Qualification::whereIn('id', $this->selectedQualifications)
        ->with('qualificationCategory')
        ->get();
}

public function updatedSelectedQualifications()
{
    $this->loadSelectedQualifications();
}

    public function updatedSelectedCategory()
    {
        $this->updateQualificationsByCategory();
    }

    private function updateQualificationsByCategory()
{
    if ($this->selectedCategory) {
        $this->qualificationsByCategory = Qualification::where('qualification_category_id', $this->selectedCategory)
            ->with('qualificationCategory')  // Eager load the relationship
            ->orderBy('qualification_name')
            ->get();
    } else {
        $this->qualificationsByCategory = collect();
    }
}

public function addQualification()
{
    if ($this->newQualification && !in_array($this->newQualification, $this->selectedQualifications)) {
        $this->selectedQualifications[] = $this->newQualification;
        $this->loadSelectedQualifications();
        $this->newQualification = '';
    }
    $this->selectedCategory = null;
    $this->updateQualificationsByCategory();
}

    public function removeQualification($qualificationId)
{
    $this->selectedQualifications = array_values(array_diff($this->selectedQualifications, [$qualificationId]));
    $this->loadSelectedQualifications();
}

    public function createJobSeeker()
    {
        $this->validate();

        $user = User::create([
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'user_type' => 'Candidate',
        ]);

        if ($this->profileImage) {
            $imagePath = $this->profileImage->store('profile-images', 's3');
            $user->image = $imagePath;
            $user->save();
        }

        $student = Student::create([
            'user_id' => $user->id,
            'name_kanji' => $this->name_kanji,
            'name_katakana' => $this->name_katakana,
            'name_japanese' => $this->name_japanese,
            'contact_phone_number' => $this->contact_phone_number,
            'created_by' => Auth::id(),
        ]);

        $candidate = Candidate::create([
            'student_id' => $student->id,
            'publish_category' => $this->publish_category,
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'nationality' => $this->nationality,
            'last_education' => $this->last_education,
            'work_history' => $this->work_history,
            'self_presentation' => $this->self_presentation,
            'personal_preference' => $this->personal_preference,
            'japanese_language_qualification' => $this->japanese_language_qualification,
            'desired_job_type' => $this->desired_job_type,
            'other_request' => $this->other_request,
            'created_by' => Auth::id(),
        ]);

        $candidate->qualifications()->attach($this->selectedQualifications);

        if ($this->cvImage) {
            $cvImagePath = $this->cvImage->store('cv-images', 's3');
            $candidate->profile_picture_link = $cvImagePath;
            $candidate->save();
        }

        flash()->success('Job Seeker created successfully.');
        return redirect()->route('business-operator.job-seekers.index');
    }

    public function render()
    {
        return view('livewire.business-operator.create-job-seeker');
    }
}
