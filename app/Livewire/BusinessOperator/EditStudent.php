<?php

namespace App\Livewire\BusinessOperator;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditStudent extends Component
{
    use WithFileUploads;

    public Student $student;
    public $name;
    public $email;
    public $nameKanji;
    public $nameKatakana;
    public $nameJapanese;
    public $contactPhoneNumber;
    public $image;
    public $tempImage;
    public $isEditing = false;
    public $showDeleteConfirmation = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'nameKanji' => 'required|string|max:255',
        'nameKatakana' => 'required|string|max:255',
        'nameJapanese' => 'required|string|max:255',
        'contactPhoneNumber' => 'required|string|max:20',
        'tempImage' => 'nullable|image|max:1024',
    ];

    public function mount(Student $student)
    {
        $this->student = $student;
        $this->loadStudentData();
    }

    public function loadStudentData()
    {
        $this->name = $this->student->user->name;
        $this->email = $this->student->user->email;
        $this->nameKanji = $this->student->name_kanji;
        $this->nameKatakana = $this->student->name_katakana;
        $this->nameJapanese = $this->student->name_japanese;
        $this->contactPhoneNumber = $this->student->contact_phone_number;
        $this->image = $this->student->user->image;
    }

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->loadStudentData();
        $this->tempImage = null;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $this->student->user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            if ($this->tempImage) {
                if ($this->student->user->image) {
                    Storage::disk('s3')->delete($this->student->user->image);
                }
                $this->student->user->image = $this->tempImage->store('student-images', 's3');
                $this->student->user->save();
            }

            $this->student->update([
                'name_kanji' => $this->nameKanji,
                'name_katakana' => $this->nameKatakana,
                'name_japanese' => $this->nameJapanese,
                'contact_phone_number' => $this->contactPhoneNumber,
            ]);
        });

        $this->isEditing = false;
        $this->loadStudentData();
        flash()->success('Student updated successfully.');
    }

    public function deleteImage()
    {
        if ($this->student->user->image) {
            Storage::disk('s3')->delete($this->student->user->image);
            $this->student->user->image = null;
            $this->student->user->save();
            $this->image = null;
        }
    }

    public function deleteTempImage()
    {
        $this->tempImage = null;
    }

    public function confirmDelete()
    {
        $this->showDeleteConfirmation = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
    }

    public function deleteStudent()
    {
        DB::transaction(function () {
            if ($this->student->user->image) {
                Storage::disk('s3')->delete($this->student->user->image);
            }
            $this->student->user->delete();
            $this->student->delete();
        });

        flash()->success('Student deleted successfully.');
        return redirect()->route('business-operator.students');
    }

    public function render()
    {
        return view('livewire.business-operator.edit-student');
    }
}
