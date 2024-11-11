<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Vacancy;
use App\Models\Company;
use Livewire\WithPagination;

class VacancyList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCompany = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCompany()
    {
        $this->resetPage();
    }

    public function render()
    {
        $vacanciesQuery = Vacancy::query()
            ->with('company') // Eager load the company relationship
            ->when($this->selectedCompany, function ($query) {
                return $query->where('company_id', $this->selectedCompany);
            })
            ->where(function ($query) {
                $query->where('job_title', 'like', '%' . $this->search . '%')
                    ->orWhere('publish_category', 'like', '%' . $this->search . '%');
            });

        $vacancies = $vacanciesQuery->paginate(10);

        return view('livewire.business-operator.vacancy-list', [
            'vacancies' => $vacancies,
            'companies' => Company::orderBy('name')->get(),
        ]);
    }
}
