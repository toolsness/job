<?php

namespace App\Livewire\Company;

use Livewire\Component;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\InterviewTimeSlot;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Rules\ValidInterviewTime;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;

class InterviewScheduleManagement extends Component
{
    use WithPagination;

    public $vacancy = null;
    public $companyId;
    public $selectedDate;
    public $startTime;
    public $endTime;
    public $availableSlots = [];
    public $showModal = false;
    public $editingSlotId = null;
    public $currentMonth;
    public $currentYear;
    public $isEditing = false;
    public $viewMode = 'all'; // 'all' or 'personal'
    public $companyUsers = [];
    public $selectedUserId;

    protected $rules = [
        'startTime' => ['required', 'date_format:H:i'],
        'endTime' => ['required', 'date_format:H:i', 'after:startTime'],
    ];

    public function mount($vacancyId = null)
    {
        $user = Auth::user();
        $this->companyId = $this->getCompanyId($user);

        if ($vacancyId) {
            $this->vacancy = Vacancy::findOrFail($vacancyId);
        }

        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->loadAvailableSlots();
        $this->loadCompanyUsers();
        $this->selectedUserId = Auth::id();
    }

    public function updatedSelectedUserId()
    {
        $this->resetValidation();
    }

    public function loadCompanyUsers()
    {
        $this->companyUsers = User::whereHas('companyAdmin', function ($query) {
            $query->where('company_id', $this->companyId);
        })->orWhereHas('companyRepresentative', function ($query) {
            $query->where('company_id', $this->companyId);
        })->get();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->loadAvailableSlots();
    }

    public function loadAvailableSlots()
    {
        $query = InterviewTimeSlot::where('company_id', $this->companyId)
            ->where('status', 'available')
            ->whereYear('date', $this->currentYear)
            ->whereMonth('date', $this->currentMonth)
            ->orderBy('date')
            ->orderBy('start_time');

        if ($this->vacancy) {
            $query->where('vacancy_id', $this->vacancy->id);
        } else {
            $query->whereNull('vacancy_id');
        }

        if ($this->viewMode === 'personal') {
            $query->where('user_id', Auth::id());
        }

        $slots = $query->with(['user' => function ($query) {
            $query->select('id', 'name', 'image', 'user_type');
        }])->get();

        $this->availableSlots = $slots->groupBy(function ($slot) {
            return $slot->date->format('Y-m-d');
        })->toArray();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->showModal = true;
        $this->resetValidation();
        $this->reset(['startTime', 'endTime', 'editingSlotId', 'isEditing']);
        $this->selectedUserId = Auth::id();
        $this->dispatch('popup-opened');
    }

    public function addOrUpdateTimeSlot()
    {
        $this->validate($this->getValidationRules());

        if ($this->isEditing) {
            $this->updateTimeSlot();
        } else {
            $this->addTimeSlot();
        }
    }

    public function addTimeSlot()
    {
        InterviewTimeSlot::create([
            'company_id' => $this->companyId,
            'user_id' => $this->selectedUserId,
            'vacancy_id' => $this->vacancy ? $this->vacancy->id : null,
            'date' => $this->selectedDate,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'status' => 'available',
        ]);

        $this->reset(['startTime', 'endTime']);
        $this->loadAvailableSlots();
        $this->showModal = false;
    }

    public function updateTimeSlot()
    {
        $slot = InterviewTimeSlot::findOrFail($this->editingSlotId);

        if (Auth::user()->user_type === 'CompanyAdmin' || $slot->user_id === Auth::id()) {
            $slot->update([
                'start_time' => $this->startTime,
                'end_time' => $this->endTime,
                'user_id' => $this->selectedUserId,
            ]);

            $this->reset(['startTime', 'endTime', 'editingSlotId', 'isEditing']);
            $this->loadAvailableSlots();
            $this->showModal = false;
        } else {
            $this->addError('unauthorized', 'You are not authorized to edit this slot.');
        }
    }

    public function editTimeSlot($slotId)
    {
        $this->editingSlotId = $slotId;
        $slot = InterviewTimeSlot::findOrFail($slotId);

        if (Auth::user()->user_type === 'CompanyAdmin' || $slot->user_id === Auth::id()) {
            $this->startTime = Carbon::parse($slot->start_time)->format('H:i');
            $this->endTime = Carbon::parse($slot->end_time)->format('H:i');
            $this->selectedUserId = $slot->user_id;
            $this->isEditing = true;
            $this->selectedDate = $slot->date->format('Y-m-d');

            $this->showModal = true;
        } else {
            $this->addError('unauthorized', 'You are not authorized to edit this slot.');
        }
    }

    public function cancelEdit()
    {
        $this->reset(['startTime', 'endTime', 'editingSlotId', 'isEditing']);
        $this->selectedUserId = Auth::id();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->getValidationRules());
    }

    public function deleteTimeSlot($slotId)
    {
        $slot = InterviewTimeSlot::findOrFail($slotId);
        if (Auth::user()->user_type === 'CompanyAdmin' || $slot->user_id === Auth::id()) {
            $slot->delete();
            $this->loadAvailableSlots();
        } else {
            $this->addError('unauthorized', 'You are not authorized to delete this slot.');
        }
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadAvailableSlots();
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadAvailableSlots();
    }

    private function getCompanyId($user)
    {
        if ($user->user_type === 'CompanyAdmin') {
            return $user->companyAdmin->company_id;
        } elseif ($user->user_type === 'CompanyRepresentative') {
            return $user->companyRepresentative->company_id;
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    private function getValidationRules()
    {
        $rules = $this->rules;

        $validInterviewTimeRule = new ValidInterviewTime(
            $this->selectedDate,
            $this->companyId,
            $this->editingSlotId,
            $this->selectedUserId
        );

        $rules['startTime'][] = $validInterviewTimeRule;
        $rules['endTime'][] = $validInterviewTimeRule;

        return $rules;
    }

    public function render()
    {
        $calendar = $this->generateCalendar();
        $userType = Auth::user()->user_type;

        $paginatedSlots = InterviewTimeSlot::where('company_id', $this->companyId)
            ->where('date', $this->selectedDate)
            ->orderBy('start_time')
            ->paginate(5);

        return view('livewire.company.interview-schedule-management', compact('calendar', 'userType', 'paginatedSlots'));
    }

    private function generateCalendar()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $firstDayOfWeek = $date->dayOfWeek;

        $calendar = [];
        $week = array_fill(0, 7, null);

        // Fill in the days before the first day of the month
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $week[$i] = null;
        }

        // Fill in the days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $week[$firstDayOfWeek] = $day;
            $firstDayOfWeek++;

            if ($firstDayOfWeek === 7 || $day === $daysInMonth) {
                $calendar[] = $week;
                $week = array_fill(0, 7, null);
                $firstDayOfWeek = 0;
            }
        }

        return $calendar;
    }
}
