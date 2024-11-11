<div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
    <a                            href="{{route('job-seeker.search')}}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2">
        <span class="text-xl font-bold">Job Seeker Search</span>
        {{--                <span class="text-gray-600">Find job opportunities</span> --}}
    </a>

    <a        href="{{ route('job-interviews') }}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2">
        <span class="text-xl font-bold">List of Interview Status</span>
        {{--                <span class="text-gray-600">Prepare for interviews</span> --}}
    </a>

    <a href="{{ route('job-list.search') }}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2">
        <span class="text-xl font-bold">List of job registration information</span>
        {{--                <span class="text-gray-600">Apply for Japanese lessons</span> --}}
    </a>

    <a href="{{ route('company.edit-profile') }}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2">
        <span class="text-xl font-bold">User Registration</span>
        {{--                <span class="text-gray-600">Register as a user</span> --}}
    </a>

    <a href="{{ route('messages') }}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2">
        <span class="text-xl font-bold flex items-center">
            Message @livewire('unread-message-count')
        </span>

    </a>
</div>
