<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended('/'); // Change '/dashboard' to your desired path
    }
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = Auth::user();
    $defaultView = in_array($user->user_type, ['CompanyRepresentative', 'CompanyAdmin']) ? 'company' : 'student';

    return redirect()->intended(RouteServiceProvider::HOME)->cookie('default_view', $defaultView, 60 * 24 * 365);
}

public function destroy(Request $request): RedirectResponse
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    $defaultView = Cookie::get('default_view', 'student');

    return redirect()->route('home', ['viewType' => $defaultView]);
}
}
