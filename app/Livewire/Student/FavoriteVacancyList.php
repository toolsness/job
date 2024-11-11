<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;

class FavoriteVacancyList extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $candidate = Auth::user()->student->candidate;
        $favoriteVacancies = $candidate->favoriteVacancies()
            ->with(['company', 'vacancyCategory'])
            ->when($this->search, function ($query) {
                $query->where('job_title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('company', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->paginate(10);

        return view('livewire.student.favorite-vacancy-list', [
            'favoriteVacancies' => $favoriteVacancies,
        ]);
    }

    public function removeFromFavorites($vacancyId)
    {
        $candidate = Auth::user()->student->candidate;
        $candidate->favoriteVacancies()->detach($vacancyId);
        $this->dispatch('favorite-updated');
    }
}
