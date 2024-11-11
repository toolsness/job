<div>
    <div class="flex items-center justify-center min-h-screen px-4 bg-white sm:px-6 lg:px-8">
        <div class="w-full max-w-3xl space-y-8">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900">Application Confirmation</h1>
            </div>

            <div class="overflow-hidden bg-white border border-gray-200 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h2 class="text-lg font-medium leading-6 text-gray-900">Application Details</h2>
                    <p class="max-w-2xl mt-1 text-sm text-gray-500">Job Posting Number:
                        {{ $interview->interview_schedule_id }}</p>
                </div>
                <div class="px-4 py-5 border-t border-gray-200 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $interview->vacancy->company->name }}</dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Job Title</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $interview->vacancy->job_title }}</dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Candidate Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $interview->candidate->student->user->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Interviewer</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $interview->inchargeUser->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Interview Date & Time</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if ($interview->implementation_date && $interview->implementation_start_time)
                                {{ $interview->implementation_date->format('F d, Y') }} at
                                {{ $interview->implementation_start_time->format('H:i') }}
                                @else
                                N/A
                                @endif
                            </dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Interview Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $interview->id }}</dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ Auth::user()->user_type === 'Candidate' ? $interview->status->getDisplayText() : $interview->status->value }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="p-4 border-l-4 border-yellow-400 bg-yellow-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            {{ $confirmationMessage }}
                        </p>
                    </div>
                </div>
            </div>

            @if ($interview->status !== \App\Enum\InterviewStatus::CANCELLATION_REFUSAL)
                <div class="space-y-4 text-sm text-gray-700">
                    <p>• The interview will be conducted via Zoom.</p>
                    <p>• Please make a note of the date, time, interviewer's name, and interview number. (You can also
                        check this information in the interview status list on your My Page.)</p>
                    <p>• The Zoom ID will be sent to your registered email address by 12:00 PM Japan time on
                        @if ($interview->implementation_date) {{ $interview->implementation_date->subDay()->format('F j') }} @else 1 day before the interview @endif.</p>
                    <p>• If you do not receive the email, please contact the administration office.</p>
                    <p>• For Zoom installation and usage instructions, click <a href="#"
                            class="text-blue-600 hover:underline">here</a>.</p>
                </div>
            @endif

            <div class="flex flex-col items-center space-y-4">
                <a href="{{ route('job-interviews') }}"
                    class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm sm:w-64 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Interview Status List
                </a>
                @if ($interview->status == \App\Enum\InterviewStatus::INTERVIEW_CONFIRMED || $interview->status == \App\Enum\InterviewStatus::APPLICATION_FROM_STUDENTS)
                    <button wire:click="openCancelModal"
                        class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm sm:w-64 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel Application
                    </button>
                @endif
            </div>

            <div class="flex flex-wrap justify-center gap-4 text-sm">
                <a href="{{ route('job-details', $interview->vacancy) }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Return to Job Details
                </a>
                <a href="{{ route('job-list.search') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Return to Job Search
                </a>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Return to TOP Page
                </a>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    @if ($showCancelModal)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            Cancel Interview
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Please provide a reason for cancelling the interview:
                            </p>
                            <textarea wire:model.defer="cancelReason"
                                class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                rows="3"></textarea>
                            @error('cancelReason')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="cancelInterview"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Cancellation
                        </button>
                        <button type="button" wire:click="$set('showCancelModal', false)"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
