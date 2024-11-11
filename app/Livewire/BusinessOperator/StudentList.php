<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Student;
use App\Models\Candidate;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class StudentList extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $showModal = false;
    public $selectedStudentId;
    public $candidateId;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->candidateId = $this->getCandidateId($studentId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function getCandidateId($studentId)
    {
        $candidate = Candidate::where('student_id', $studentId)->first();
        return $candidate ? $candidate->id : null;
    }

    public function render()
    {
        $students = Student::query()
            ->with('user')
            ->where(function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('name_kanji', 'like', '%' . $this->search . '%')
                ->orWhere('name_katakana', 'like', '%' . $this->search . '%')
                ->orWhere('name_japanese', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.business-operator.student-list', [
            'students' => $students,
        ]);
    }
}
