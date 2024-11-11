<?php

namespace App\Livewire\BusinessOperator;

use App\Models\Candidate;
use App\Models\Country;
use App\Models\Qualification;
use App\Models\QualificationCategory;
use App\Models\VacancyCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditJobSeeker extends Component
{
    use WithFileUploads;

    public Candidate $jobSeeker;
    public $name;
    public $email;
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
    public $tempProfileImage;
    public $cvImage;
    public $tempCvImage;
    public $isEditing = false;
    public $showDeleteConfirmation = false;
    public $publish_category;

    public $qualificationCategories;
    public $selectedCategory = null;
    public $qualificationsByCategory = [];
    public $selectedQualifications = [];
    public $newQualification;
    public $selectedQualificationsDetails;
    public $jobTypes;
    public $desired_job_type;
    public $japanese_language_qualification;
    public $other_request;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
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
        'tempProfileImage' => 'nullable|image|max:1024',
        'tempCvImage' => 'nullable|image|max:1024',
        'publish_category' => 'required|in:NotPublished,Published,PublicationStopped',
        'selectedQualifications' => 'array',
        'selectedQualifications.*' => 'exists:qualifications,id',
        'desired_job_type' => 'required|exists:vacancy_categories,id',
        'japanese_language_qualification' => 'required|string',
        'other_request' => 'nullable|string',
    ];

    public function mount(Candidate $jobSeeker)
    {
        $this->jobSeeker = $jobSeeker;
        $this->countries = Country::orderBy('country_name')->get();
        $this->qualifications = Qualification::orderBy('qualification_name')->get();
        $this->qualificationCategories = QualificationCategory::orderBy('name')->get();
        $this->jobTypes = VacancyCategory::orderBy('name')->get();
        $this->loadJobSeekerData();
        $this->loadSelectedQualifications();
    }

    public function loadJobSeekerData()
    {
        $this->name = $this->jobSeeker->name;
        $this->email = $this->jobSeeker->student->user->email;
        $this->name_kanji = $this->jobSeeker->student->name_kanji;
        $this->name_katakana = $this->jobSeeker->student->name_katakana;
        $this->name_japanese = $this->jobSeeker->student->name_japanese;
        $this->contact_phone_number = $this->jobSeeker->student->contact_phone_number;
        $this->gender = $this->jobSeeker->gender;
        $this->birth_date = $this->jobSeeker->birth_date ? $this->jobSeeker->birth_date->format('Y-m-d') : null;
        $this->nationality = $this->jobSeeker->nationality;
        $this->last_education = $this->jobSeeker->last_education;
        $this->work_history = $this->jobSeeker->work_history;
        $this->self_presentation = $this->jobSeeker->self_presentation;
        $this->personal_preference = $this->jobSeeker->personal_preference;
        $this->profileImage = $this->jobSeeker->student->user->image;
        $this->cvImage = $this->jobSeeker->profile_picture_link;
        $this->publish_category = $this->jobSeeker->publish_category;
        $this->desired_job_type = $this->jobSeeker->desired_job_type;
        $this->japanese_language_qualification = $this->jobSeeker->japanese_language_qualification;
        $this->other_request = $this->jobSeeker->other_request;
        $this->selectedQualifications = $this->jobSeeker->qualifications->pluck('id')->toArray();
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
                ->with('qualificationCategory')
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

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->loadJobSeekerData();
    }

    public function updateJobSeeker()
    {
        $this->validate();

        $this->jobSeeker->student->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->tempProfileImage) {
            if ($this->jobSeeker->student->user->image) {
                Storage::disk('s3')->delete($this->jobSeeker->student->user->image);
            }
            $imagePath = $this->tempProfileImage->store('profile-images', 's3');
            $this->jobSeeker->student->user->image = $imagePath;
            $this->jobSeeker->student->user->save();
        }

        $this->jobSeeker->student->update([
            'name_kanji' => $this->name_kanji,
            'name_katakana' => $this->name_katakana,
            'name_japanese' => $this->name_japanese,
            'contact_phone_number' => $this->contact_phone_number,
            'updated_by' => Auth::id(),
        ]);

        $this->jobSeeker->update([
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date ? \Carbon\Carbon::parse($this->birth_date) : null,
            'nationality' => $this->nationality,
            'last_education' => $this->last_education,
            'work_history' => $this->work_history,
            'self_presentation' => $this->self_presentation,
            'personal_preference' => $this->personal_preference,
            'publish_category' => $this->publish_category,
            'desired_job_type' => $this->desired_job_type,
            'japanese_language_qualification' => $this->japanese_language_qualification,
            'other_request' => $this->other_request,
            'updated_by' => Auth::id(),
        ]);

        $this->jobSeeker->qualifications()->sync($this->selectedQualifications);

        if ($this->tempCvImage) {
            if ($this->jobSeeker->profile_picture_link) {
                Storage::disk('s3')->delete($this->jobSeeker->profile_picture_link);
            }
            $cvImagePath = $this->tempCvImage->store('cv-images', 's3');
            $this->jobSeeker->profile_picture_link = $cvImagePath;
            $this->jobSeeker->save();
        }

        $this->isEditing = false;
        flash()->success('Job Seeker updated successfully.');
    }

    public function confirmDelete()
    {
        $this->showDeleteConfirmation = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
    }

    public function deleteJobSeeker()
    {
        $this->jobSeeker->delete();
        session()->flash('message', 'Job Seeker deleted successfully.');
        return redirect()->route('business-operator.job-seekers.index');
    }

    public function deleteCvImage()
    {
        if ($this->jobSeeker->profile_picture_link) {
            Storage::disk('s3')->delete($this->jobSeeker->profile_picture_link);
            $this->jobSeeker->profile_picture_link = null;
            $this->jobSeeker->save();
            $this->cvImage = null;
        }
    }

    public function deleteTempCvImage()
    {
        $this->tempCvImage = null;
    }

    public function render()
    {
        return view('livewire.business-operator.edit-job-seeker');
    }
}
