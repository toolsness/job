<div class="container px-4 py-8 mx-auto" x-data="{ scrollToTimeSection: @entangle('shouldScrollToTimeSection') }" x-init="$watch('scrollToTimeSection', value => { if (value) { $nextTick(() => { document.getElementById('interview-time-section').scrollIntoView({ behavior: 'smooth' }); }); } })">
    <h1 class="mb-8 text-2xl font-bold text-center">Interview Application</h1>

    <div class="p-6 mb-8 bg-white rounded-lg shadow-md">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p><strong>Job Posting Number:</strong> {{ $vacancy->id }}</p>
                <p class="mt-2"><strong>Company Name:</strong> {{ $companyName }}</p>
                <p class="mt-2"><strong>Business Type:</strong> {{ $vacancy->job_title }}</p>
            </div>
            <div>
                <p><strong>Monthly Salary:</strong> {{ $vacancy->monthly_salary }}</p>
                <p class="mt-2"><strong>Work Location:</strong> {{ $vacancy->work_location }}</p>
            </div>
        </div>
        <div class="mt-4 text-right">
            <a href="{{ route('job-details', $vacancy) }}"
                class="px-4 py-2 transition duration-300 bg-gray-200 rounded-md hover:bg-gray-300">
                Return to Job Details
            </a>
        </div>
    </div>

    <h2 class="mb-4 text-xl font-semibold">Please select an interview date</h2>

    {{-- <div class="flex justify-center mb-4 space-x-4">
        <button wire:click="setViewMode('all')"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 {{ $viewMode === 'all' ? 'bg-blue-800' : '' }}">
            All Slots
        </button>
        <button wire:click="setViewMode('recommended')"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 {{ $viewMode === 'recommended' ? 'bg-blue-800' : '' }}">
            Recommended Slots
        </button>
    </div> --}}

    <div class="p-6 mb-8 bg-white rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-4">
            <button wire:click="previousMonth" class="text-blue-600 hover:text-blue-800">◀ Previous Month</button>
            <h3 class="text-lg font-semibold">{{ Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
            </h3>
            <button wire:click="nextMonth" class="text-blue-600 hover:text-blue-800">Next Month ▶</button>
        </div>
        <div class="grid grid-cols-7 gap-2 mb-2">
            @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                <div class="p-2 font-medium text-center bg-gray-100">{{ $day }}</div>
            @endforeach
        </div>
        <div class="grid grid-cols-7 gap-2">
            @foreach ($calendar as $week)
                @foreach ($week as $day)
                    @if ($day)
                        @php
                            $currentDate = Carbon\Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                            $isRecommended = in_array($currentDate, $recommendedDates);
                            $isAvailable = in_array($currentDate, $availableDates);
                            $isUnavailable = in_array($currentDate, $unavailableDates);
                        @endphp
                        <div class="border p-2 h-24 relative {{ $isRecommended || $isAvailable ? 'cursor-pointer hover:bg-blue-100' : 'bg-gray-100' }} {{ $selectedDate == $currentDate ? 'bg-green-100' : '' }}"
                        wire:click="{{ $isRecommended || $isAvailable ? '$set(\'selectedDate\', \'' . $currentDate . '\')' : '' }}">
                            <span class="absolute top-1 left-1">{{ $day }}</span>
                            @if ($isRecommended)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-6 h-6 bg-green-100 border-2 border-green-500 rounded-full"></div>
                                </div>
                            @elseif($isAvailable)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-6 h-6 border-2 border-blue-500 rounded-full"></div>
                                </div>
                            @elseif($isUnavailable)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="h-24 p-2 bg-gray-100 border"></div>
                    @endif
                @endforeach
            @endforeach
        </div>

    </div>

    <div class="mb-4">
        {{-- <p class="text-sm">
            <span class="inline-block w-4 h-4 mr-2 bg-green-100 border-2 border-green-500 rounded-full"></span>
            Recommended Available Slots for This Job Interview (Low chance of interview schedule being cancelled And may get quick result...)
        </p> --}}
        <p class="text-sm">
            <span class="inline-block w-4 h-4 mr-2 border-2 border-blue-500 rounded-full"></span> Available Slots
        </p>
        <p class="text-sm">
            <span class="inline-block w-4 h-4 mr-2 text-red-500">✕</span> Slots Reserved / Not Available
        </p>
    </div>

    @if ($selectedDate)
        <h2 class="mb-4 text-xl font-semibold" id="interview-time-section">Please select an interview time</h2>
        <div class="space-y-2">
            @foreach ($availableSlots as $slot)
                <button wire:click="$set('selectedSlotId', {{ $slot['id'] }})"
                    class="w-full py-4 text-center rounded-md border {{ $selectedSlotId == $slot['id'] ? 'bg-green-400 border-green-600' : 'bg-white border-gray-300 hover:bg-gray-100' }}">
                    {{ $slot['start_time'] }} - {{ $slot['end_time'] }}
                    @if ($slot['vacancy_id'] == $vacancy->id)
                        <span class="px-2 py-1 ml-2 text-xs text-green-800 bg-green-200 rounded-full">Recommended</span>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="mt-8 text-center">
            <button wire:click="applyForInterview" wire:target="applyForInterview" wire:loading.remove
                class="px-8 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 {{ $selectedSlotId ? '' : 'opacity-50 cursor-not-allowed' }}"
                {{ $selectedSlotId ? '' : 'disabled' }}>
                Reserve this time
            </button>
                        <div wire:loading wire:target="applyForInterview"
                            class="inline-flex items-center justify-center">
                            <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                                    Reserving...</span></span>
                        </div>
        </div>
    @endif
</div>
