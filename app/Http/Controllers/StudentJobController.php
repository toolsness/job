<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;

class StudentJobController extends Controller
{
    public function show(Vacancy $vacancy)
    {
        return view('student.job-details', compact('vacancy'));
    }

    public function search()
    {
        // Implement job search logic here
        return view('student.job-search');
    }
}
