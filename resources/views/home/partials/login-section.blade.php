@php
    $viewType = $viewType ?? 'student';
    $toggleViewType = $viewType === 'student' ? 'company' : 'student';
    $toggleRoute = $viewType === 'student' ? route('company.home') : route('student.home');
@endphp

<section class="bg-white rounded-lg shadow-md border border-black border-solid p-6 max-w-md w-full">
    <h2 class="text-lg font-bold mb-4">New Member Registration / Login</h2>
    <p class="text-gray-600 mb-6">We support everything from specific skill job searches, applications, and employment support.</p>
    <p class="text-gray-600 mb-6">If you have not yet registered as a member, register as a new member now!</p>
    <div class="flex flex-col gap-4">
        <a href="{{ $viewType === 'student' ? route('student.new-member-registration') : route('company.new-member-registration') }}"
           class="bg-red-600 text-white font-bold py-2 px-4 rounded-md hover:bg-red-700 transition-colors duration-300">
            New Member Registration
        </a>
        <a href="{{ $viewType === 'student' ? route('student.login') : route('company.login') }}"
           class="bg-blue-500 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-600 transition-colors duration-300">
            Login
        </a>
    </div>
    <div class="mt-4 text-center">
        <a href="{{ route('test.login.index') }}" class="text-sm text-blue-600 hover:text-gray-900 underline">
            Try Different User Types: Quick Login Page for Testers<br>(Testing Accounts Available)
        </a>
    </div>
</section>
