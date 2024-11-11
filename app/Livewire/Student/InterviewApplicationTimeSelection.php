<?php

namespace App\Livewire\Student;

use App\Enum\InterviewStatus;
use App\Enum\ReservationStatus;
use App\Models\Interview;
use App\Models\InterviewSchedule;
use App\Models\InterviewTimeSlot;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewApplicationNotification;
use App\Mail\InterviewConfirmedNotification;
use Spatie\CalendarLinks\Link;
use Illuminate\Support\Facades\Gate;

class InterviewApplicationTimeSelection extends Component
{
    public $vacancy;

    public $selectedDate;

    public $selectedSlotId;

    public $availableDates = [];

    public $recommendedDates = [];

    public $unavailableDates = [];

    public $availableSlots = [];

    public $viewMode = 'all'; // 'all' or 'recommended'

    public $currentMonth;

    public $currentYear;

    public $interview;

    public $shouldScrollToTimeSection = false;

    public function mount($vacancyId = null, $interviewId = null)
    {
        if (!Gate::allows('view-candidate-details', [$vacancyId, $interviewId])) {
            abort(403);
        }

        $this->interview = $interviewId ? Interview::findOrFail($interviewId) : null;

        if ($vacancyId) {
            $this->vacancy = Vacancy::findOrFail($vacancyId);
        } elseif ($this->interview) {
            $this->vacancy = $this->interview->vacancy;
        }

        if (! $this->vacancy) {
            throw new \Exception('No vacancy specified');
        }

        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->loadDates();
    }

    public function loadDates()
    {
        $companyId = $this->getCompanyId();

        if (!$companyId) {
            throw new \Exception('Unable to determine company for this vacancy');
        }

        $slots = InterviewTimeSlot::where('company_id', $companyId)
            ->whereYear('date', $this->currentYear)
            ->whereMonth('date', $this->currentMonth)
            ->where('status', 'available')
            ->get();

        $this->availableDates = [];
        $this->recommendedDates = [];
        $this->unavailableDates = [];

        foreach ($slots as $slot) {
            $date = $slot->date->format('Y-m-d');
            if ($slot->vacancy_id === null) {
                $this->availableDates[] = $date;
            } elseif ($slot->vacancy_id === $this->vacancy->id) {
                $this->recommendedDates[] = $date;
            } else {
                $this->unavailableDates[] = $date;
            }
        }

        $this->availableDates = array_unique($this->availableDates);
        $this->recommendedDates = array_unique($this->recommendedDates);
        $this->unavailableDates = array_unique($this->unavailableDates);
    }

    private function getCompanyId()
    {
        if ($this->vacancy->companyRepresentative) {
            return $this->vacancy->companyRepresentative->company_id;
        } elseif ($this->vacancy->companyAdmin) {
            return $this->vacancy->companyAdmin->company_id;
        }

        return null;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadDates();
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadDates();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->updatedSelectedDate($this->selectedDate);
    }

    public function updatedSelectedDate($value)
    {
        if ($value) {
            $query = InterviewTimeSlot::where('company_id', $this->vacancy->company_id)
                ->whereDate('date', $value)
                ->where('status', 'available')
                ->orderBy('start_time');

            if ($this->viewMode === 'recommended') {
                $query->where(function ($q) {
                    $q->where('vacancy_id', $this->vacancy->id)
                        ->orWhereNull('vacancy_id');
                });
            }

            $this->availableSlots = $query->get()->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($slot->end_time)->format('H:i'),
                    'vacancy_id' => $slot->vacancy_id,
                ];
            });
        } else {
            $this->availableSlots = [];
        }
        $this->selectedSlotId = null;

        $this->shouldScrollToTimeSection = true;
    }

    public function getCompanyName()
    {
        if ($this->vacancy->companyRepresentative) {
            return $this->vacancy->companyRepresentative->company->name;
        } elseif ($this->vacancy->companyAdmin) {
            return $this->vacancy->companyAdmin->company->name;
        }

        return 'N/A';
    }

    public function applyForInterview()
    {
        $this->validate([
            'selectedSlotId' => 'required|exists:interview_time_slots,id',
        ]);

        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            flash()->error('Student profile not found.');
            return redirect()->route('student.profile');
        }

        $candidate = $student->candidate;

        if (!$candidate) {
            flash()->error('Candidate profile not found.');
            return redirect()->route('student.candidate-details', ['vacancyId' => $this->vacancy->id]);
        }

        $slot = InterviewTimeSlot::findOrFail($this->selectedSlotId);

        // Create or update InterviewSchedule
        $interviewSchedule = InterviewSchedule::updateOrCreate(
            [
                'vacancy_id' => $this->vacancy->id,
                'interview_date' => $slot->date,
            ],
            [
                'interview_start_time' => $slot->date->setTimeFromTimeString($slot->start_time),
                'reservation_status' => $this->interview ? ReservationStatus::RESERVED : ReservationStatus::PENDING,
            ]
        );

        if ($this->interview) {
            $this->interview->update([
                'incharge_user_id' => $slot->user_id,
                'implementation_date' => $slot->date,
                'implementation_start_time' => $slot->date->setTimeFromTimeString($slot->start_time),
                'booking_confirmation_date' => Carbon::now(),
                'status' => InterviewStatus::INTERVIEW_CONFIRMED,
                'interview_schedule_id' => $interviewSchedule->id,
            ]);

            // Send email for confirmed interview
            $this->sendInterviewConfirmedNotification($this->interview);
        } else {
            $this->interview = Interview::create([
                'candidate_id' => $candidate->id,
                'vacancy_id' => $this->vacancy->id,
                'interview_schedule_id' => $interviewSchedule->id,
                'incharge_user_id' => $slot->user_id,
                'implementation_date' => $slot->date,
                'implementation_start_time' => $slot->date->setTimeFromTimeString($slot->start_time),
                'booking_request_date_student' => Carbon::now(),
                'status' => InterviewStatus::APPLICATION_FROM_STUDENTS,
            ]);

            // Send email for new interview application
            $this->sendInterviewApplicationNotification($this->interview);
        }

        $slot->update([
            'status' => 'booked',
            'vacancy_id' => $this->vacancy->id,
        ]);

        return redirect()->route('student.interview.confirmation', ['interview' => $this->interview->id]);
    }

    private function sendInterviewApplicationNotification($interview)
    {
        $inChargeEmail = $interview->inchargeUser->email;
        Mail::to($inChargeEmail)->send(new InterviewApplicationNotification($interview));
    }

    private function sendInterviewConfirmedNotification($interview)
    {
        $inChargeEmail = $interview->inchargeUser->email;
        Mail::to($inChargeEmail)->send(new InterviewConfirmedNotification($interview));
    }

    public function render()
    {
        $calendar = $this->generateCalendar();

        return view('livewire.student.interview-application-time-selection', [
            'companyName' => $this->getCompanyName(),
            'calendar' => $calendar,
        ]);
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
