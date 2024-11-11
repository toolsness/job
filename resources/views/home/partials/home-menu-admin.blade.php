<div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

    <a

        href="{{route('business-operator.job-seekers.index')}}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2"
    >
        <span class="text-xl font-bold">Job Seeker Search</span>
        {{--                <span class="text-gray-600">Find job opportunities</span>--}}
    </a>

    <a

        href="{{route('business-operator.vacancies.index')}}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2"
    >
        <span class="text-xl font-bold">Register for Job Postings</span>
        {{--                <span class="text-gray-600">Prepare for interviews</span>--}}
    </a>

    <a

        href="{{ route('business-operator.interviews.index') }}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2"
    >
        <span class="text-xl font-bold">Status of Interview Examination</span>
        {{--                <span class="text-gray-600">Apply for Japanese lessons</span>--}}
    </a>

    <a

        href="{{ route('business-operator.vacancy-categories') }}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2"
    >
        <span class="text-xl font-bold">Industry Management</span>
        {{--                <span class="text-gray-600">Apply for Japanese lessons</span>--}}
    </a>

    <a

        href="{{ route('business-operator.qualifications.management') }}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2"
    >
        <span class="text-xl font-bold">Qualifications Management</span>
        {{--                <span class="text-gray-600">Apply for Japanese lessons</span>--}}
    </a>

    <a

        href="{{ route('business-operator.news-notices') }}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2"
    >
        <span class="text-xl font-bold">News & Notices Management</span>
        {{--                <span class="text-gray-600">Apply for Japanese lessons</span>--}}
    </a>

    <a

        href="{{ route('messages') }}"
        class="bg-white rounded-lg shadow-md border border-black border-solid p-6 hover:bg-gray-50 transition-colors duration-300 flex flex-col items-center gap-2">
        <span class="text-xl font-bold flex items-center">
            Message @livewire('unread-message-count')
        </span>
    </a>

</div>
