<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentInterviewController extends Controller
{
    public function index()
    {
        $interviews = Auth::user()->student->interviews()->latest()->get();
        return view('student.interview-status', compact('interviews'));
    }

    public function cancelInterview(Interview $interview)
    {
        // Implement cancellation logic here
        // For example:
        $interview->update(['status' => 'cancelled']);
        $interview->interviewTimeSlot->update(['status' => 'available']);

        return redirect()->route('student.interview-status')->with('success', 'Interview cancelled successfully');
    }
}
