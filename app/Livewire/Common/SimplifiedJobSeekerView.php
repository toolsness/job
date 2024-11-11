<?php

namespace App\Livewire\Common;

use Livewire\Component;
use App\Models\Candidate;

class SimplifiedJobSeekerView extends Component
{
    public $candidate;

    public function mount($id)
    {
        $this->candidate = Candidate::with(['student.user', 'country', 'qualifications.qualificationCategory', 'desiredJobType'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.common.simplified-job-seeker-view');
    }
}
