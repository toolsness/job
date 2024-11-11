@php
    $viewType = $viewType ?? 'student';
    $toggleViewType = $viewType === 'student' ? 'company' : 'student';

    $currentRoute = Route::currentRouteName();

    if (strpos($currentRoute, 'login') !== false) {
        $toggleRoute = $viewType === 'student' ? route('company.login') : route('student.login');
    } elseif (strpos($currentRoute, 'new-member-registration') !== false) {
        $toggleRoute = $viewType === 'student' ? route('company.new-member-registration') : route('student.new-member-registration');
    } elseif (strpos($currentRoute, 'password.request') !== false) {
        $toggleRoute = $viewType === 'student' ? route('company.password.request') : route('student.password.request');
    } else {
        $toggleRoute = $viewType === 'student' ? route('company.home') : route('student.home');
    }
@endphp

<section class="w-full max-w-screen-xl mx-auto px-1 py-3 sm:px-6 lg:px-1">
    <div class="flex flex-col items-center sm:items-start space-y-4 max-w-xs mx-auto sm:mx-0">
        <p class="underline-offset-8 cursor-not-allowed px-2 py-3 text-base sm:text-lg font-semibold text-white rounded-md border border-black shadow-md transition-colors duration-300 w-[200px] text-center
            {{ $viewType === 'student' ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700' }}">
            {{ $viewType === 'student' ? 'Student Only' : 'Company Only' }}
        </p>
        <div class="flex items-center space-x-2 text-sm w-full justify-center sm:justify-start">
            <nav class="text-black font-bold text-center text-lg">
                <a href="{{ $toggleRoute }}" class="toggle-view-type text-cyan-500 underline">Click here</a> for {{ $toggleViewType }}
            </nav>
        </div>
    </div>
</section>
