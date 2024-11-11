<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Interview;
use App\Models\InterviewSchedule;
use App\Models\InterviewTimeSlot;
use App\Enum\InterviewStatus;
use App\Enum\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UpdateInterviewStatuses extends Command
{
    protected $signature = 'app:update-interview-statuses';
    protected $description = 'Update interview statuses, reservation statuses, and manage interview time slots';

    public function handle()
    {
        $this->info('Starting to update interview statuses and manage time slots...');
        try {
            $now = Carbon::now();
            DB::beginTransaction();

            // Update interview statuses
            $interviews = Interview::with('interviewSchedule')
                ->where('status', InterviewStatus::INTERVIEW_CONFIRMED)
                ->get();

            $updatedCount = 0;
            foreach ($interviews as $interview) {
                if ($interview->interviewSchedule) {
                    $scheduleDate = Carbon::parse($interview->interviewSchedule->interview_date);
                    $scheduleTime = Carbon::parse($interview->interviewSchedule->interview_start_time);
                    $scheduleDateTime = $scheduleDate->setTime(
                        $scheduleTime->hour,
                        $scheduleTime->minute,
                        $scheduleTime->second
                    );
                    if ($scheduleDateTime <= $now) {
                        $interview->status = InterviewStatus::INTERVIEW_CONDUCTED;
                        $interview->result = 'Pending';
                        $interview->save();
                        $interview->interviewSchedule->reservation_status = ReservationStatus::COMPLETE;
                        $interview->interviewSchedule->save();
                        $updatedCount++;
                    }
                }
            }

            // Remove expired slots
            InterviewTimeSlot::where('date', '<', $now->toDateString())
                ->orWhere(function ($query) use ($now) {
                    $query->where('date', $now->toDateString())
                        ->where('end_time', '<', $now->toTimeString());
                })
                ->delete();

            // Reinstate slots for cancelled interviews
            // $cancelledInterviews = Interview::whereIn('status', [
            //     InterviewStatus::CANCELLATION_REFUSAL,
            //     InterviewStatus::OFFER_WITHDRAWN,
            //     InterviewStatus::INTERVIEW_CANCELLED,
            // ])
            //     ->where('implementation_date', '>=', now()->toDateString())
            //     ->get();

            // foreach ($cancelledInterviews as $interview) {
            //     InterviewTimeSlot::updateOrCreate(
            //         [
            //             'company_id' => $interview->vacancy->company_id,
            //             'date' => $interview->implementation_date,
            //             'start_time' => $interview->implementation_start_time,
            //         ],
            //         [
            //             'end_time' => Carbon::parse($interview->implementation_start_time)->addMinutes(10),
            //             'status' => 'available',
            //             'user_id' => $interview->incharge_user_id,
            //         ]
            //     );
            // }

            DB::commit();
            $this->info("Interview statuses updated successfully. {$updatedCount} interviews were updated.");
            Log::info("Updated {$updatedCount} interviews to INTERVIEW_CONDUCTED status and their schedules to COMPLETE.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("An error occurred: " . $e->getMessage());
            Log::error("Error updating interview statuses: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
        $this->info('Interview status update and time slot management completed.');
    }
}
