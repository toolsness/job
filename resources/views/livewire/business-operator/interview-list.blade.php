<div class="container px-4 py-8 mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-center">List of Interviews</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="relative">
            <input wire:model.live="search" type="text" placeholder="Search interviews..."
                class="w-full px-4 py-2 pr-10 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <div>
            <select wire:model.live="filterStatus"
                class="w-full px-4 py-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Statuses</option>
                @foreach(App\Enum\InterviewStatus::cases() as $status)
                    <option value="{{ $status->value }}">{{ $status->name }}</option>
                @endforeach
            </select>
        </div>
        <div></div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#3AB2E3]">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">
                        <a wire:click.prevent="sortBy('id')" role="button" href="#" class="flex items-center">
                            Interview ID
                            @include('home.partials._sort-icon', ['field' => 'id'])
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Candidate Name</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Candidate Country</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Interview Schedule</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Company Name</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Vacancy ID</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Job Title</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Interviewer Name</th>

                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Employment Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Interview Result</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Result Notification</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Booking Request (Student)</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Booking Request (Company)</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Booking Confirmed</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($interviews as $interview)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $interview->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $interview->candidate->name }}
                            <br>
                                <span class="text-xs text-gray-500">
                                    @if($interview->candidate->student->user->email)
                                        Email: {{ $interview->candidate->student->user->email }}
                                    @endif
                                </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $interview->candidate->country->country_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($interview->interviewSchedule)
                                Time: {{ $interview->interviewSchedule->interview_start_time ? \Carbon\Carbon::parse($interview->interviewSchedule->interview_start_time)->format('h:i A') : 'N/A' }}
                                <br>
                                Date: {{ $interview->interviewSchedule->interview_date ? \Carbon\Carbon::parse($interview->interviewSchedule->interview_date)->format('d F, Y') : 'N/A' }}
                            @else
                                No Schedule
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $interview->vacancy->company->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $interview->vacancy->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $interview->vacancy->job_title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($interview->inchargeUser)
                                {{ $interview->inchargeUser->name }}
                                <br>
                                <span class="text-xs text-gray-500">
                                    @if($interview->inchargeUser->companyAdmin)
                                        Company Admin
                                        <br>
                                        Email: {{ $interview->inchargeUser->email }}
                                    @elseif($interview->inchargeUser->companyRepresentative)
                                        Company Representative
                                        <br>
                                        Email: {{ $interview->inchargeUser->email }}
                                    @else
                                        Unknown
                                    @endif
                                </span>
                            @else
                                Not Assigned
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">{{ $interview->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($interview->employment_contract_procedure_application_date)
                                Started at {{ $interview->employment_contract_procedure_application_date->format('d F, Y') }}
                            @else
                                Not started yet
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $interview->result === 'NotApplicable' ? 'Not Applicable' : $interview->result }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $interview->result_notification_date ? $interview->result_notification_date->format('d F, Y') : 'Not Notified Yet' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $interview->booking_request_date_student ? $interview->booking_request_date_student->format('d F, Y') : 'Requested by Company' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $interview->booking_request_date_company ? $interview->booking_request_date_company->format('d F, Y') : 'Not Requested by Student' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $interview->booking_confirmation_date ? $interview->booking_confirmation_date->format('d F, Y') : 'Not Confirmed Yet' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('business-operator.interviews.edit', $interview) }}"
                                class="px-3 py-1 font-bold text-white bg-orange-500 rounded hover:bg-orange-600">
                                More
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $interviews->links() }}
    </div>

    <div class="flex items-center justify-between gap-3 mt-6">
        <div class="w-1/3"></div>

        <a href="{{ route('home') }}" class="px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
            Return to TOP
        </a>

        <div class="w-1/3"></div>
    </div>
</div>
