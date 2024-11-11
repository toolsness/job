<div>
    {{-- <div wire:poll.1s>
        Testing Current time: {{ now()->format('l jS \\of F Y h:i:s A') }} || Timezone: {{ now()->timezone }}
    </div> --}}
    <div class="flex items-center justify-center px-16 py-20 text-black bg-white max-md:px-5">
        <div class="flex flex-col max-w-full w-[785px] max-md:mt-5">
            <h2 class="pb-6 text-2xl font-bold text-center underline">Interview Status List</h2>
            <div class="grid grid-cols-2 gap-8 px-2 py-2 bg-gray-100 border border-black rounded-md">
                <button type="button" wire:click="toggleShowArchived"
                    class="self-end gap-4 px-5 py-3 text-center bg-white border border-gray-300 rounded-md hover:bg-gray-100">
                    <span class="my-auto">{{ $showArchived ? 'Hide Archived' : 'Show Archived' }}</span>
                </button>
                <button type="button" wire:click="toggleFilters"
                    class="self-end px-5 py-3 text-center bg-white border border-gray-300 rounded-md hover:bg-gray-100">
                    <i class="fa-solid fa-filter shrink-0 aspect-[1.52] fill-black w-[22px]"></i>
                    <span class="my-auto">{{ $showFilters ? 'Hide Filters' : 'Show Filters' }}</span>
                </button>
            </div>

            @if ($showFilters)
                <div class="p-4 px-2 py-2 mt-4 bg-gray-100 border border-black rounded-md">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="filterStatus" class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="filterStatus" id="filterStatus"
                                class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All</option>
                                @foreach ($statuses as $status)
                                    @if ($status !== \App\Enum\InterviewStatus::ARCHIVED)
                                        <option value="{{ $status->value }}">{{ $status->value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="filterDateFrom" class="block text-sm font-medium text-gray-700">Date
                                From</label>
                            <input type="date" wire:model="filterDateFrom" id="filterDateFrom"
                                class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="filterDateTo" class="block text-sm font-medium text-gray-700">Date To</label>
                            <input type="date" wire:model="filterDateTo" id="filterDateTo"
                                class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 space-x-2">
                        <button wire:click="applyFilters"
                            class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Apply Filters</button>
                        <button wire:click="resetFilters"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Reset
                            Filters</button>
                    </div>
                </div>
            @endif

            @if ($interviews->isNotEmpty())
                @foreach ($interviews as $interview)
                    <section
                        class="flex flex-col px-8 py-6 mt-8 border border-black rounded-lg shadow-md max-md:px-5 max-md:mt-5 max-md:max-w-full">
                        <div class="grid grid-cols-2 gap-52">
                            <div
                                class="items-center self-start justify-center px-8 py-2 mb-4 text-sm text-center bg-gray-100 border border-gray-300 rounded whitespace-nowrap max-md:px-3">
                                Status
                            </div>
                            <div
                                class="items-center self-end justify-center px-8 py-2 mb-4 text-sm font-bold text-center max-md:px-3">
                                Interview Details
                            </div>
                        </div>

                        <div class="flex gap-3 max-md:flex-wrap max-md:max-w-full ">
                            <div
                                class="items-center self-start justify-center px-4 py-4 text-base font-semibold text-center border border-gray-300 rounded-md grow whitespace-nowrap w-fit max-md:px-3">
                                {{ Auth::user()->user_type === 'Candidate' ? $interview->status->getDisplayText() : $interview->status->value }}
                            </div>
                            <div
                                class="flex flex-col items-center justify-center py-3 pl-4 pr-10 bg-gray-100 border border-gray-300 rounded-md grow shrink-0 basis-0 w-fit max-md:px-3">
                                @if ($interview->status == \App\Enum\InterviewStatus::SCOUTED)
                                    <div class="text-xs text-center">
                                        Scouted At:
                                        @if ($interview)
                                            <span
                                                class="pr-0.5 font-bold">{{ $interview->created_at->format('l jS \\of F Y') }},</span>
                                            <span class="font-bold ">
                                                {{ $interview->created_at->format('h:i A') }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm">Interview ID: <span
                                            class='font-bold'>{{ $interview->id }}</span></div>
                                @elseif ($interview->status == \App\Enum\InterviewStatus::INTERVIEW_CONDUCTED)
                                    <div class="text-xs text-center">
                                        Conducted:
                                        @if ($interview->interviewSchedule)
                                            <span class="pr-0.5 font-bold">
                                                {{ $interview->interviewSchedule->interview_date->format('l jS \\of F Y') }},
                                            </span>
                                            <span class="font-bold">
                                                @if ($interview->interviewSchedule->interview_start_time)
                                                    @if (is_string($interview->interviewSchedule->interview_start_time))
                                                        {{ \Carbon\Carbon::parse($interview->interviewSchedule->interview_start_time)->format('h:i A') }}
                                                    @else
                                                        {{ $interview->interviewSchedule->interview_start_time->format('h:i A') }}
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm">Interview ID: <span
                                            class='font-bold'>{{ $interview->id }}</span></div>
                                @elseif ($interview->status == \App\Enum\InterviewStatus::UNOFFICIAL_OFFER)
                                    <div class="text-xs text-center">
                                        Result Published At:
                                        @if ($interview->result_notification_date)
                                            <span
                                                class="pr-0.5 font-bold">{{ $interview->result_notification_date->format('l jS \\of F Y') }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm">Interview ID: <span
                                            class='font-bold'>{{ $interview->id }}</span></div>
                                @elseif ($interview->status == \App\Enum\InterviewStatus::INTERVIEW_UNSUCCESSFUL)
                                    <div class="text-xs text-center">
                                        Result Published At:
                                        @if ($interview->result_notification_date)
                                            <span
                                                class="pr-0.5 font-bold">{{ $interview->result_notification_date->format('l jS \\of F Y') }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm">Interview ID: <span
                                            class='font-bold'>{{ $interview->id }}</span></div>
                                @elseif ($interview->status == \App\Enum\InterviewStatus::INTERVIEW_CONFIRMED)
                                    <div class="text-xs text-center">
                                        Schedule:
                                        @if ($interview->interviewSchedule)
                                            <span class="pr-0.5 font-bold">
                                                {{ $interview->interviewSchedule->interview_date->format('l jS \\of F Y') }},
                                            </span>
                                            <span class="font-bold">
                                                @if ($interview->interviewSchedule->interview_start_time)
                                                    @if (is_string($interview->interviewSchedule->interview_start_time))
                                                        {{ \Carbon\Carbon::parse($interview->interviewSchedule->interview_start_time)->format('h:i A') }}
                                                    @else
                                                        {{ $interview->interviewSchedule->interview_start_time->format('h:i A') }}
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm">Interview ID: <span
                                            class='font-bold'>{{ $interview->id }}</span></div>
                                @elseif ($interview->status == \App\Enum\InterviewStatus::APPLICATION_FROM_STUDENTS)
                                    <div class="text-xs text-center">
                                        Requested Schedule:
                                        @if ($interview->interviewSchedule)
                                            <span class="pr-0.5 font-bold">
                                                {{ $interview->interviewSchedule->interview_date->format('l jS \\of F Y') }},
                                            </span>
                                            <span class="font-bold">
                                                @if ($interview->interviewSchedule->interview_start_time)
                                                    @if (is_string($interview->interviewSchedule->interview_start_time))
                                                        {{ \Carbon\Carbon::parse($interview->interviewSchedule->interview_start_time)->format('h:i A') }}
                                                    @else
                                                        {{ $interview->interviewSchedule->interview_start_time->format('h:i A') }}
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm">Interview ID: <span
                                            class='font-bold'>{{ $interview->id }}</span></div>
                                @elseif ($interview->status == \App\Enum\InterviewStatus::EMPLOYMENT_APPLICATION)
                                    <div class="text-xs text-center">
                                        Employment Contract Procedure Start At:
                                        @if ($interview && $interview->employment_contract_procedure_application_date)
                                            <span class="pr-0.5 font-bold">
                                                @if (is_string($interview->employment_contract_procedure_application_date))
                                                    {{ \Carbon\Carbon::parse($interview->employment_contract_procedure_application_date)->format('l jS \\of F Y') }}
                                                @else
                                                    {{ $interview->employment_contract_procedure_application_date->format('l jS \\of F Y') }}
                                                @endif
                                            </span>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm">Interview ID: <span
                                            class='font-bold'>{{ $interview->id }}</span></div>
                                @else
                                    <div class="text-sm">Interview ID: {{ $interview->id }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 text-sm max-md:max-w-full">
                            <span><strong>Name of Person in Charge:</strong></span>
                            {{ $interview->inchargeUser->name ?? 'N/A' }}
                        </div>

                        <div class="mt-6 text-sm max-md:max-w-full"><strong>Job ID:</strong>
                            {{ $interview->vacancy->id }}</div>

                        @if ($interview->status === \App\Enum\InterviewStatus::INTERVIEW_CONFIRMED)
                            @if ($interview->zoom_link)
                                <div class="mt-4 text-sm">
                                    <p><strong>Zoom Link:</strong> <a href="{{ $interview->zoom_link }}"
                                            target="_blank"
                                            class="text-blue-600 underline hover:font-semibold hover:animate-bounce">Click
                                            to Open Link</a>
                                        @if (Auth::user()->user_type !== 'Candidate')
                                            or
                                            <button wire:loading.attr="disabled" wire:loading.remove type="button"
                                                wire:click="sendNotification({{ $interview->id }})"
                                                class="justify-center col-span-1 text-green-700 underline font-semibold hover:font-extrabold hover:animate-pulse hover:text-green-950">
                                                Send Notification
                                            </button><span wire:loading wire:target="sendNotification"
                                                class="ml-1 font-bold text-green-700"><i
                                                    class="fa fa-spinner fa-spin"></i>
                                                Sending Mail to Candidate...</span>
                                        @endif
                                    </p>
                                </div>
                            @elseif (Auth::user()->user_type == 'Candidate')
                                <div class="mt-6 font-bold text-left text-md">Zoom Link</div>
                                <div class="mt-4 text-sm font-semibold text-green-600">
                                    You will be notified by email once the zoom link is available. But for now save the
                                    schedule on your device.
                                </div>
                            @elseif (Auth::user()->user_type !== 'Candidate')
                                <div class="mt-6 font-bold text-left text-md">Zoom Link</div>
                                <div class="mt-4 text-sm font-semibold text-green-600">
                                    Please <button type="button" wire:click="openZoomLinkModal({{ $interview->id }})"
                                        class="underline animate-pulse hover:font-bold"><strong>Add a Zoom
                                            Link</strong></button> and save the schedule on your device.
                                </div>
                            @endif
                            <div class="mt-2 pt-2 grid-cols-4 space-x-4">
                                <p class="text-sm grid-span-1 underline"><strong>Click Icon to Save Schedule</strong>
                                <div class="flex">
                                    <a class="hover:animate-spin"
                                        href="{{ $this->getGoogleCalendarLink($interview) }}" target="_blank"
                                        title="Add to Google Calendar">
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48"
                                            height="48" viewBox="0 0 48 48">
                                            <path fill="#FFC107"
                                                d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
                                            </path>
                                            <path fill="#FF3D00"
                                                d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
                                            </path>
                                            <path fill="#4CAF50"
                                                d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
                                            </path>
                                            <path fill="#1976D2"
                                                d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a class="hover:animate-spin"
                                        href="{{ $this->getAppleCalendarLink($interview) }}" target="_blank"
                                        title="Add to Apple Calendar">
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48"
                                            height="48" viewBox="0 0 24 24">
                                            <path
                                                d="M 16.125 1 C 14.972 1.067 13.648328 1.7093438 12.861328 2.5273438 C 12.150328 3.2713438 11.589359 4.3763125 11.818359 5.4453125 C 13.071359 5.4783125 14.329031 4.8193281 15.082031 3.9863281 C 15.785031 3.2073281 16.318 2.12 16.125 1 z M 16.193359 5.4433594 C 14.384359 5.4433594 13.628 6.5546875 12.375 6.5546875 C 11.086 6.5546875 9.9076562 5.5136719 8.3476562 5.5136719 C 6.2256562 5.5146719 3 7.4803281 3 12.111328 C 3 16.324328 6.8176563 21 8.9726562 21 C 10.281656 21.013 10.599 20.176969 12.375 20.167969 C 14.153 20.154969 14.536656 21.011 15.847656 21 C 17.323656 20.989 18.476359 19.367031 19.318359 18.082031 C 19.922359 17.162031 20.170672 16.692344 20.638672 15.652344 C 17.165672 14.772344 16.474672 9.1716719 20.638672 8.0136719 C 19.852672 6.6726719 17.558359 5.4433594 16.193359 5.4433594 z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a class="hover:animate-spin"
                                        href="{{ $this->getOutlookCalendarLink($interview) }}" target="_blank"
                                        title="Add to Outlook Calendar">
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48"
                                            height="48" viewBox="0 0 48 48">
                                            <path fill="#ff5722" d="M6 6H22V22H6z" transform="rotate(-180 14 14)">
                                            </path>
                                            <path fill="#4caf50" d="M26 6H42V22H26z" transform="rotate(-180 34 14)">
                                            </path>
                                            <path fill="#ffc107" d="M26 26H42V42H26z" transform="rotate(-180 34 34)">
                                            </path>
                                            <path fill="#03a9f4" d="M6 26H22V42H6z" transform="rotate(-180 14 34)">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if ($interview->status === \App\Enum\InterviewStatus::INTERVIEW_CONDUCTED)
                            <div class="mt-6 font-bold text-left text-md">Interview Results</div>
                            @if (Auth::user()->user_type === 'Candidate')
                                <div class="mt-4 text-sm font-semibold text-green-600">
                                    Interview has been conducted. Please wait for the result.
                                </div>
                            @endif
                        @endif
                        @if ($interview->status === \App\Enum\InterviewStatus::EMPLOYMENT_APPLICATION)
                            @if (Auth::user()->user_type === 'Candidate')
                                <div class="mt-6 font-bold text-left text-md">Congratulations!</div>
                                <div class="mt-4 text-sm font-semibold text-green-600">
                                    We will now proceed with your employment application with this company. Please wait
                                    for our office to contact you within one business day.
                                </div>
                            @endif
                        @endif
                        @if ($interview->status === \App\Enum\InterviewStatus::HIRED)
                            @if (Auth::user()->user_type === 'Candidate')
                                <div class="mt-6 font-bold text-left text-md">Congratulations!</div>
                                <div class="mt-4 text-sm font-semibold text-green-600">
                                    You have been hired by this company.
                                </div>
                            @endif
                        @endif
                        <div
                            class="grid grid-cols-3 gap-4 mt-8 text-sm text-center whitespace-nowrap max-md:mt-5 max-md:max-w-full">
                            @if ($interview->status !== \App\Enum\InterviewStatus::ARCHIVED)
                                @if (Auth::user()->user_type === 'Candidate')
                                    @if ($interview->status === \App\Enum\InterviewStatus::SCOUTED)
                                        <button type="button"
                                            wire:click="openModal('approveScout', {{ $interview->id }})"
                                            class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                            Accept
                                        </button>
                                        <button type="button"
                                            wire:click="openModal('declineScout', {{ $interview->id }})"
                                            class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                            Decline
                                        </button>
                                    @else
                                        @switch($interview->status)
                                            @case(\App\Enum\InterviewStatus::UNOFFICIAL_OFFER)
                                                <button type="button"
                                                    wire:click="openModal('employmentApplication', {{ $interview->id }})"
                                                    class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                    Apply For Employment
                                                </button>
                                                <button type="button"
                                                    wire:click="openModal('declineOffer', {{ $interview->id }})"
                                                    class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                    Decline Offer
                                                </button>
                                            @break

                                            @case (\App\Enum\InterviewStatus::APPLICATION_FROM_STUDENTS)
                                                <button type="button"
                                                    wire:click="openModal('cancelInterviewSlot', {{ $interview->id }})"
                                                    class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                    Cancel Interview
                                                </button>
                                                <div class="col-span-1"></div>
                                            @break

                                            @case(\App\Enum\InterviewStatus::INTERVIEW_CONFIRMED)
                                                <button type="button"
                                                    wire:click="openModal('cancelInterviewSlot', {{ $interview->id }})"
                                                    class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                    Cancel Interview
                                                </button>
                                                <div class="col-span-1"></div>
                                            @break

                                            @case(\App\Enum\InterviewStatus::INTERVIEW_CONDUCTED)
                                                <button type="button"
                                                    wire:click="openModal('withdrawOffer', {{ $interview->id }})"
                                                    class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                    Withdrawal offer
                                                </button>
                                                <div class="col-span-1"></div>
                                            @break

                                            @default
                                                <div class="col-span-2"></div>
                                        @endswitch
                                    @endif
                                @else
                                    @switch($interview->status)
                                        @case(\App\Enum\InterviewStatus::SCOUTED)
                                            <button type="button"
                                                wire:click="openModal('cancelScout', {{ $interview->id }})"
                                                class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                Cancel
                                            </button>
                                            <div class="col-span-1"></div>
                                        @break

                                        @case(\App\Enum\InterviewStatus::UNOFFICIAL_OFFER)
                                            <button type="button"
                                                wire:click="openModal('withdrawOffer', {{ $interview->id }})"
                                                class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                Withdrawal of job offer
                                            </button>
                                            <button type="button"
                                                wire:click="openModal('employmentApplication', {{ $interview->id }})"
                                                class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                Employment Procedures
                                            </button>
                                        @break

                                        @case(\App\Enum\InterviewStatus::INTERVIEW_CONFIRMED)
                                            <button type="button"
                                                wire:click="openModal('cancelInterviewSlot', {{ $interview->id }})"
                                                class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                Cancel Interview
                                            </button>
                                            {{-- <div class="col-span-1"></div> --}}
                                            @if (!$interview->zoom_link)
                                                <button type="button" wire:click="openZoomLinkModal({{ $interview->id }})"
                                                    class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                    Add Zoom Link
                                                </button>
                                            @else
                                                <button type="button" wire:click="openZoomLinkModal({{ $interview->id }})"
                                                    class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                    Edit Zoom Link
                                                </button>
                                            @endif
                                        @break

                                        @case(\App\Enum\InterviewStatus::APPLICATION_FROM_STUDENTS)
                                            <button type="button"
                                                wire:click="openModal('refuseInterviewSlot', {{ $interview->id }})"
                                                class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                Refusal
                                            </button>
                                            <button type="button"
                                                wire:click="openModal('finalizeInterviewSchedule', {{ $interview->id }})"
                                                class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                Finalize Interview Schedule
                                            </button>
                                        @break

                                        @case(\App\Enum\InterviewStatus::INTERVIEW_CONDUCTED)
                                            <div class="grid grid-cols-2 col-span-2 gap-2">
                                                <button type="button"
                                                    wire:click="openModal('unsuccessfulInterview', {{ $interview->id }})"
                                                    class="justify-center px-4 py-3 text-xs bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-2">
                                                    Unsuccessful
                                                </button>
                                                <button type="button"
                                                    wire:click="openModal('unofficialOffer', {{ $interview->id }})"
                                                    class="justify-center px-4 py-3 text-xs bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-2">
                                                    Unofficial Offer
                                                </button>
                                            </div>
                                        @break

                                        @case (\App\Enum\InterviewStatus::EMPLOYMENT_APPLICATION)
                                            {{-- <button type="button" wire:click="openModal('hire', {{ $interview->id }})"
                                                class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                                Hire
                                            </button> --}}
                                            <div class="col-span-1"></div>
                                            <div class="col-span-1"></div>
                                        @break

                                        @default
                                            <div class="col-span-2"></div>
                                    @endswitch
                                @endif

                                @if (in_array($interview->status, [
                                        \App\Enum\InterviewStatus::CANCELLATION_REFUSAL,
                                        \App\Enum\InterviewStatus::INTERVIEW_UNSUCCESSFUL,
                                        \App\Enum\InterviewStatus::OFFER_WITHDRAWN,
                                        \App\Enum\InterviewStatus::OFFER_DECLINED,
                                        \App\Enum\InterviewStatus::HIRED,
                                    ]))
                                    <button wire:click="openModal('archiveInterview', {{ $interview->id }})"
                                        class="col-span-2 justify-center px-8 py-3 max-w-[220px] bg-white rounded-md border border-gray-300 hover:bg-gray-100 max-md:px-3">
                                        Archive
                                    </button>
                                @endif
                            @else
                                @if (Auth::user()->user_type !== 'Candidate')
                                    <button wire:click="openModal('deleteInterview', {{ $interview->id }})"
                                        class="justify-center col-span-1 px-8 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                                        Delete
                                    </button>
                                    <div class="col-span-1"></div>
                                @else
                                    <div class="col-span-1"></div>
                                    <div class="col-span-1"></div>
                                @endif
                            @endif

                            <a href="{{ route($interviewDetailsRoute, $interview) }}"
                                class="col-span-1 justify-center px-8 py-3 bg-white rounded-md border border-gray-300 hover:bg-gray-100 max-md:px-3 h-[42px] flex items-center">
                                Details
                            </a>
                        </div>
                    </section>
                @endforeach

                <div class="mt-8">
                    {{ $interviews->links() }}
                </div>
            @else
                <p class="mt-4 text-center text-gray-500">No interviews found.</p>
            @endif

            <!-- Footer Links -->
            <div class="flex justify-center max-w-full gap-5 mt-16 max-md:flex-wrap max-md:mt-10">
                @if ($vacancyId)
                    <a href="{{ route('job-details', ['id' => $vacancyId]) }}"
                        class="items-center justify-center px-8 py-4 text-sm text-center bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                        Return to Job Details
                    </a>
                @endif
                <a href="{{ route('job-list.search') }}"
                    class="items-center justify-center px-8 py-4 text-sm text-center bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                    Return to Job Search
                </a>
                <a href="{{ route('home') }}"
                    class="items-center justify-center px-8 py-4 text-sm text-center bg-white border border-gray-300 rounded-md hover:bg-gray-100 max-md:px-3">
                    Return to TOP Page
                </a>
            </div>
        </div>
    </div>

    <!-- Main Modal -->
    <x-popup wire:model="showModal">
        <div class="p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                @switch($modalType)
                    @case('approveScout')
                        Approve Scouting Request
                    @break

                    @case('declineScout')
                        Decline Scouting Request
                    @break

                    @case('cancelScout')
                        Cancel Scouting Request
                    @break

                    @case('withdrawOffer')
                        Withdraw Offer
                    @break

                    @case('cancelInterview')
                        Cancel Interview
                    @break

                    @case('refuseInterview')
                        Refuse Interview
                    @break

                    @case('unsuccessfulInterview')
                        Unsuccessful Interview
                    @break

                    @case('declineOffer')
                        Decline Offer
                    @break

                    @case('archiveInterview')
                        Archive
                    @break

                    @case('deleteInterview')
                        Delete
                    @break

                    @case('cancelInterviewSlot')
                        Cancel Interview
                    @break

                    @case('refuseInterviewSlot')
                        Refuse Interview
                    @break

                    @case ('hire')
                        Hire Candidate
                    @break

                    @default
                        {{ ucfirst(str_replace('_', ' ', $modalType)) }}
                @endswitch
            </h3>
            <p class="mt-2 text-sm text-gray-500">
                @switch($modalType)
                    @case('approveScout')
                        Are you sure you want to approve this scouting request? This will take you to the candidate
                        details page where you have to confirm your CV for further processing.
                    @break

                    @case('declineScout')
                        Are you sure you want to decline this scouting request? This action cannot be undone.
                    @break

                    @case('cancelScout')
                        Are you sure you want to cancel this scouting request? This action cannot be undone.
                    @break

                    @case('withdrawOffer')
                        Are you sure you want to withdraw this offer? This action cannot be undone.
                    @break

                    @case('cancelInterview')
                        Are you sure you want to cancel this interview? This action cannot be undone.
                    @break

                    @case('refuseInterview')
                        Are you sure you want to refuse this interview? This action cannot be undone.
                    @break

                    @case('cancelInterviewSlot')
                        Are you sure you want to cancel this interview? This action cannot be undone.
                    @break

                    @case('refuseInterviewSlot')
                        Are you sure you want to refuse this interview? This action cannot be undone.
                    @break

                    @case('unsuccessfulInterview')
                        Are you sure you want to mark this interview as unsuccessful? This action cannot be undone.
                    @break

                    @case('declineOffer')
                        Are you sure you want to decline this offer? This action cannot be undone.
                    @break

                    @case('archive')
                        Are you sure you want to archive this? This action cannot be undone.
                    @break

                    @case('delete')
                        Are you sure you want to delete this? This action cannot be undone.
                    @break

                    @case('hire')
                        Are you sure you want to hire this candidate? This action cannot be undone.
                    @break

                    @default
                        Are you sure you want to proceed with this action?
                @endswitch
            </p>
            @php
                $userType = Auth::user()->user_type;
                $reasons = [];

                if ($userType === 'CompanyRepresentative' || $userType === 'CompanyAdmin') {
                    $reasons = [
                        'declineScout' => [
                            'Position has been filled' => 'Position has been filled',
                            'Candidate qualifications do not match requirements' =>
                                'Candidate qualifications do not match requirements',
                            'Insufficient relevant experience' => 'Insufficient relevant experience',
                            'Budget constraints or changes' => 'Budget constraints or changes',
                            'Role requirements have changed' => 'Role requirements have changed',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'cancelScout' => [
                            'Position has been filled' => 'Position has been filled',
                            'Candidate qualifications do not match requirements' =>
                                'Candidate qualifications do not match requirements',
                            'Insufficient relevant experience' => 'Insufficient relevant experience',
                            'Budget constraints or changes' => 'Budget constraints or changes',
                            'Role requirements have changed' => 'Role requirements have changed',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'withdrawOffer' => [
                            'Changes in business requirements' => 'Changes in business requirements',
                            'Issues arising from reference checks' => 'Issues arising from reference checks',
                            'Background verification not cleared' => 'Background verification not cleared',
                            'Misrepresentation of qualifications or experience' =>
                                'Misrepresentation of qualifications or experience',
                            'Hiring freeze or budget constraints' => 'Hiring freeze or budget constraints',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'cancelInterview' => [
                            'Scheduling conflict' => 'Scheduling conflict',
                            'Interviewer unavailability' => 'Interviewer unavailability',
                            'Position temporarily on hold' => 'Position temporarily on hold',
                            'Internal candidate being considered' => 'Internal candidate being considered',
                            'Role requirements have been updated' => 'Role requirements have been updated',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'unsuccessfulInterview' => [
                            'Skills do not align with job requirements' => 'Skills do not align with job requirements',
                            'Concerns about cultural fit' => 'Concerns about cultural fit',
                            'Communication skills do not meet expectations' =>
                                'Communication skills do not meet expectations',
                            'Experience level not sufficient for the role' =>
                                'Experience level not sufficient for the role',
                            'Salary expectations do not align with budget' =>
                                'Salary expectations do not align with budget',
                            'Position has been filled' => 'Position has been filled',
                            'Candidate qualifications do not match requirements' =>
                                'Candidate qualifications do not match requirements',
                            'Insufficient relevant experience' => 'Insufficient relevant experience',
                            'Budget constraints or changes' => 'Budget constraints or changes',
                            'Role requirements have changed' => 'Role requirements have changed',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'refuseInterview' => [
                            'Scheduling conflict' => 'Scheduling conflict',
                            'Interviewer unavailability' => 'Interviewer unavailability',
                            'Position temporarily on hold' => 'Position temporarily on hold',
                            'Internal candidate being considered' => 'Internal candidate being considered',
                            'Role requirements have been updated' => 'Role requirements have been updated',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'refuseInterviewSlot' => [
                            'Scheduling conflict' => 'Scheduling conflict',
                            'Interviewer unavailability' => 'Interviewer unavailability',
                            'Position temporarily on hold' => 'Position temporarily on hold',
                            'Internal candidate being considered' => 'Internal candidate being considered',
                            'Role requirements have been updated' => 'Role requirements have been updated',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'declineOffer' => [
                            'Changes in business requirements' => 'Changes in business requirements',
                            'Issues arising from reference checks' => 'Issues arising from reference checks',
                            'Background verification not cleared' => 'Background verification not cleared',
                            'Misrepresentation of qualifications or experience' =>
                                'Misrepresentation of qualifications or experience',
                            'Hiring freeze or budget constraints' => 'Hiring freeze or budget constraints',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'withdrawOffer' => [
                            'Changes in business requirements' => 'Changes in business requirements',
                            'Issues arising from reference checks' => 'Issues arising from reference checks',
                            'Background verification not cleared' => 'Background verification not cleared',
                            'Misrepresentation of qualifications or experience' =>
                                'Misrepresentation of qualifications or experience',
                            'Hiring freeze or budget constraints' => 'Hiring freeze or budget constraints',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'cancelInterviewSlot' => [
                            'Scheduling conflict' => 'Scheduling conflict',
                            'Interviewer unavailability' => 'Interviewer unavailability',
                            'Position temporarily on hold' => 'Position temporarily on hold',
                            'Internal candidate being considered' => 'Internal candidate being considered',
                            'Role requirements have been updated' => 'Role requirements have been updated',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'hire' => [
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                    ];
                } elseif ($userType === 'Candidate') {
                    $reasons = [
                        'declineScout' => [
                            'Not interested in this role' => 'Not interested in this role',
                            'Location is not suitable' => 'Location is not suitable',
                            'Salary below expectations' => 'Salary below expectations',
                            'Accepted another Job offer' => 'Accepted another job offer',
                            'Decided to stay in current job' => 'Decided to stay in current job',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'cancelScout' => [
                            'Timing is not right for a job change' => 'Timing is not right for a job change',
                            'Role does not align with career goals' => 'Role does not align with career goals',
                            'Concerns about company culture' => 'Concerns about company culture',
                            'Insufficient information about the role' => 'Insufficient information about the role',
                            'Change in personal circumstances' => 'Change in personal circumstances',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'cancelInterview' => [
                            'Scheduling conflict' => 'Scheduling conflict',
                            'Need more time to prepare' => 'Need more time to prepare',
                            'Unable to attend for interview at scheduled time' =>
                                'Unable to attend for interview at scheduled time',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'cancelInterviewSlot' => [
                            'Scheduling conflict' => 'Scheduling conflict',
                            'Need more time to prepare' => 'Need more time to prepare',
                            'Unable to attend for interview at scheduled time' =>
                                'Unable to attend for interview at scheduled time',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'refuseInterview' => [
                            'Scheduling conflict' => 'Scheduling conflict',
                            'Need more time to prepare' => 'Need more time to prepare',
                            'Accepted another job offer' => 'Accepted another job offer',
                            'No longer interested in this role' => 'No longer interested in this role',
                            'Unable to travel for interview' => 'Unable to travel for interview',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'refuseInterviewSlot' => [
                            'Scheduling conflict' => 'Scheduling conflict',
                            'Need more time to prepare' => 'Need more time to prepare',
                            'Accepted another job offer' => 'Accepted another job offer',
                            'No longer interested in this role' => 'No longer interested in this role',
                            'Unable to travel for interview' => 'Unable to travel for interview',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'declineOffer' => [
                            'Accepted another job offer' => 'Accepted another job offer',
                            'Compensation package not satisfactory' => 'Compensation package not satisfactory',
                            'Concerns about career growth opportunities' =>
                                'Concerns about career growth opportunities',
                            'Concerns about company stability' => 'Concerns about company stability',
                            'Work-life balance concerns' => 'Work-life balance concerns',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                        'withdrawOffer' => [
                            'Accepted another job offer' => 'Accepted another job offer',
                            'Other (please specify)' => 'Other (please specify)',
                        ],
                    ];
                }
            @endphp

            @if (in_array($modalType, [
                    'declineScout',
                    'cancelScout',
                    'withdrawOffer',
                    'cancelInterview',
                    'refuseInterview',
                    'unsuccessfulInterview',
                    'declineOffer',
                    'cancelInterviewSlot',
                    'refuseInterviewSlot',
                    'hire',
                ]))
                <div class="mt-2">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                    <select wire:model.live="reason" id="reason"
                        class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select a reason</option>
                        @foreach ($reasons[$modalType] ?? [] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($reason === 'Other (please specify)')
                    <div class="mt-2">
                        <label for="other_reason" class="block text-sm font-medium text-gray-700">Please
                            specify</label>
                        <textarea wire:model.live="otherReason" id="other_reason" rows="3"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Please provide more details..."></textarea>
                    </div>
                @endif
            @endif
            <div class="mt-4 flex justify-end space-x-3">
                <button wire:click="closeModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">Cancel</button>
                <button
                    wire:click="@switch($modalType)
                                    @case('approveScout')approveScout({{ $selectedInterviewId }})@break
                            @case('declineScout')declineScout({{ $selectedInterviewId }})@break
                            @case('cancelScout')cancelScout({{ $selectedInterviewId }})@break
                            @case('finalizeInterviewSchedule')finalizeInterviewSchedule({{ $selectedInterviewId }})@break
                            @default{{ $modalType }}({{ $selectedInterviewId }})
                        @endswitch"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md">Confirm</button>
            </div>
        </div>
    </x-popup>

    <!-- Confirmation Modal -->
    <x-popup wire:model="showConfirmation">
        <div class="p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Confirmation</h3>
            <p class="mt-2 text-sm text-gray-500">{{ $confirmationMessage }}</p>
            <div class="mt-4 flex justify-end">
                <button wire:click="closeConfirmation" class="px-4 py-2 bg-blue-600 text-white rounded-md">OK</button>
            </div>
        </div>
    </x-popup>

        <!-- Interview Schedule Modal -->
        <x-popup wire:model="showScheduleModal">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Finalize Interview Schedule</h3>
                <div class="mt-2">
                    <div class="mb-4">
                        <label for="interviewDate" class="block text-sm font-medium text-gray-700">Interview
                            Date</label>
                        <input type="date" wire:model="interviewDate" id="interviewDate"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="interviewTime" class="block text-sm font-medium text-gray-700">Interview
                            Time</label>
                        <input type="time" wire:model="interviewTime" id="interviewTime"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>                <div class="mt-4 flex justify-end space-x-3">
                    <button wire:click="$set('showScheduleModal', false)" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">Cancel</button>
                    <button wire:click="saveInterviewSchedule" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
                </div>
            </div>
        </x-popup>


    <!-- Zoom Link Modal -->
    <x-popup wire:model="showZoomLinkModal">
        <div class="p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                {{ $tempZoomLink ? 'Edit Zoom Link' : 'Add Zoom Link' }}
            </h3>
            <div class="mt-2">
                <input type="url" wire:model.defer="tempZoomLink" placeholder="https://zoom.us/j/example"
                    class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none" />
            </div>
            <div class="mt-4 flex justify-end space-x-3">
                <button wire:click="closeZoomLinkModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">Cancel</button>
                <button wire:click="saveZoomLink" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
            </div>
        </div>
    </x-popup>

</div>
