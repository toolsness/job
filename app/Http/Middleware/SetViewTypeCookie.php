<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SetViewTypeCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check()) {
            $userType = Auth::user()->user_type;
            $defaultView = in_array($userType, ['CompanyRepresentative', 'CompanyAdmin']) ? 'company' : 'student';
            $response->cookie('default_view', $defaultView, 60 * 24 * 365); // 1 year
        }

        return $response;
    }
}
