<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailVerifyController extends Controller
{
    public function __invoke(Request $request, string $token, Otp $otp)
{
    $email = $request->input('email');

    $isValidated = $otp->validate($email, $token);

    if (!$isValidated->status) {
        flash()->error($isValidated->message);
        return redirect()->route('student.new-member-registration');
    }

    $newValidationToken = $otp->generate($email, 'alpha_numeric', 6, 20)->token; // 6 digits, expires in 20 minutes

    $username = explode('@', $email)[0] . '_' . Str::random(5);

    return redirect()->route('student.user-registration')
        ->with('username', $username)
        ->with('email', $email)
        ->with('token', $newValidationToken);
}
}
