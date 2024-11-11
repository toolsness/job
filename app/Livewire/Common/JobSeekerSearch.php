<?php

namespace App\Livewire\Common;

use Livewire\Component;
use App\Models\Candidate;
use App\Models\Country;
use App\Models\VacancyCategory;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class JobSeekerSearch extends Component
{
    use WithPagination;

    public $page;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $desiredIndustry = '';

    #[Url(history: true)]
    public $country = '';

    #[Url(history: true)]
    public $ageMin = 18;

    #[Url(history: true)]
    public $ageMax = 50;

    protected $queryString = ['search', 'desiredIndustry', 'country', 'ageMin', 'ageMax'];

    public function mount()
    {
        $this->fill(request()->only('search', 'desiredIndustry', 'country', 'ageMin', 'ageMax'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $candidatesQuery = Candidate::where('publish_category', 'Published')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('id', 'like', '%' . $this->search . '%')
                        ->orWhere('japanese_language_qualification', 'like', '%' . $this->search . '%')
                        ->orWhereHas('qualifications', function ($subQuery) {
                            $subQuery->where('qualification_name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('qualifications.qualificationCategory', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->desiredIndustry, function ($query) {
                $query->whereHas('desiredJobType', function ($q) {
                    $q->where('name', $this->desiredIndustry);
                });
            })
            ->when($this->country, function ($query) {
                $query->whereHas('country', function ($q) {
                    $q->where('country_name', $this->country);
                });
            })
            ->when($this->ageMin && $this->ageMax, function ($query) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN ? AND ?', [$this->ageMin, $this->ageMax]);
            });

        $candidates = $candidatesQuery->with('desiredJobType', 'country', 'qualifications')->paginate(5);

        $countries = Country::orderBy('country_name')
            ->selectRaw('countries.country_name, COUNT(candidates.id) as candidate_count')
            ->leftJoin('candidates', 'countries.id', '=', 'candidates.nationality')
            ->where('candidates.publish_category', 'Published')
            ->groupBy('countries.id', 'countries.country_name')
            ->get();

        $industries = VacancyCategory::orderBy('name')
            ->selectRaw('vacancy_categories.name, COUNT(candidates.id) as candidate_count')
            ->leftJoin('candidates', 'vacancy_categories.id', '=', 'candidates.desired_job_type')
            ->where('candidates.publish_category', 'Published')
            ->groupBy('vacancy_categories.id', 'vacancy_categories.name')
            ->get();

        return view('livewire.common.job-seeker-search', [
            'candidates' => $candidates,
            'countries' => $countries,
            'industries' => $industries,
        ]);
    }

    public function viewCandidate($candidateId)
    {
        return redirect()->route('job-seeker.view', [
            'id' => $candidateId,
            'search' => $this->search,
            'desiredIndustry' => $this->desiredIndustry,
            'country' => $this->country,
            'ageMin' => $this->ageMin,
            'ageMax' => $this->ageMax,
            'page' => $this->page
        ]);
    }
}
