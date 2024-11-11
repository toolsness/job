<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;
use App\Models\InterviewTimeSlot;
use Illuminate\Support\Facades\Auth;

class ValidInterviewTime implements ValidationRule
{
    private $selectedDate;
    private $companyId;
    private $editingSlotId;
    private $userId;

    public function __construct($selectedDate, $companyId, $editingSlotId = null, $userId = null)
    {
        $this->selectedDate = $selectedDate;
        $this->companyId = $companyId;
        $this->editingSlotId = $editingSlotId;
        $this->userId = $userId ?? Auth::id();
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->selectedDate) {
            $fail("Please select a date before setting the time.");
            return;
        }

        $dateTime = Carbon::parse($this->selectedDate . ' ' . $value);

        if ($dateTime->isPast()) {
            $fail("The scheduled time must be in the future.");
            return;
        }

        if (!$this->isTimeSlotAvailable($value)) {
            $fail("There is a conflicting schedule at this time.");
            return;
        }
    }

    private function isTimeSlotAvailable($time): bool
    {
        $query = InterviewTimeSlot::where('date', $this->selectedDate)
            ->where('company_id', $this->companyId)
            ->where('user_id', $this->userId)
            ->where(function ($query) use ($time) {
                $query->where('start_time', '<=', $time)
                      ->where('end_time', '>', $time);
            })
            ->whereIn('status', ['available', 'booked']);

        if ($this->editingSlotId) {
            $query->where('id', '!=', $this->editingSlotId);
        }

        return !$query->exists();
    }
}
