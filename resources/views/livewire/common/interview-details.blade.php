<div>
    <section class="flex items-center justify-center px-16 py-5 bg-white max-md:px-5">
        <div class="flex flex-col mt-5 w-full max-w-[945px] max-md:mt-10 max-md:max-w-full">
            <article
                class="shadow-lg flex flex-col pb-14 rounded-2xl border border-black border-solid max-md:max-w-full
    {{ $activeTab === 'resume' ? 'bg-sky-200' : ($activeTab === 'job' ? 'bg-orange-200' : 'bg-zinc-100') }}">
                <nav class="flex overflow-hidden text-xl text-center text-black">
                    <a href="#" wire:click="$set('activeTab', 'resume')"
                        class="flex-1 px-6 py-3 border-t border-l border-r relative
           {{ $activeTab === 'resume' ? 'bg-sky-200 font-bold rounded-tr-2xl rounded-tl-2xl' : 'bg-sky-200 rounded-tl-2xl rounded-tr-2xl opacity-90' }}">
                        <span class="relative z-10">Resumes of job seekers</span>
                    </a>
                    <a href="#" wire:click="$set('activeTab', 'job')"
                        class="flex-1 px-4 py-3 border-t border-r relative
           {{ $activeTab === 'job' ? 'bg-orange-200 font-bold rounded-tr-2xl' : 'bg-orange-200 rounded-tl-2xl rounded-tr-2xl opacity-90' }}">
                        <span class="relative z-10">Job Description Details</span>
                    </a>
                    <a href="#" wire:click="$set('activeTab', 'interview')"
                        class="flex-1 px-6 py-3 border-t border-r relative
           {{ $activeTab === 'interview'
               ? 'bg-zinc-100 font-bold rounded-tr-2xl'
               : 'bg-zinc-100 rounded-tl-2xl rounded-tr-2xl opacity-90' }}">
                        <span class="relative z-10">Interview Details</span>
                    </a>
                </nav>
                <div
                    class="flex justify-center items-center self-center px-16 py-20 mt-6 max-w-full bg-white rounded-2xl w-[854px] max-md:px-5">
                    @if ($activeTab === 'resume')
                        @if ($interview->candidate->id)
                            @livewire('common.simplified-job-seeker-view', ['id' => $interview->candidate->id])
                        @else
                            <p>Candidate data not found.</p>
                        @endif
                    @elseif ($activeTab === 'job')
                        @livewire('common.simplified-job-information-details', ['id' => $interview->vacancy->id])
                    @elseif ($activeTab === 'interview')
                        <div class="flex flex-col mt-2 mb-24 max-w-full w-[627px] max-md:mb-10">
                            <div class="flex gap-5 max-w-full w-[627px] max-md:flex-col max-md:gap-0">
                                <div class="w-[41%] h-[130px] max-md:w-full">

                                    <h3
                                        class=" mb-2 py-2 w-[30%] text-sm text-center bg-white border rounded-md border-black">
                                        Status</h3>
                                    <p
                                        class="px-2 py-6 text-xs font-bold text-center break-words border border-black rounded-md">
                                        {{ Auth::user()->user_type === 'Candidate' ? $interview->status->getDisplayText() : $interview->status->value }}
                                    </p>

                                </div>
                                <div class="w-[59%] max-md:w-full">
                                    <h3 class="py-2 mb-2 text-sm text-black">Scheduled Interview Date</h3>
                                    @if ($interview->interviewSchedule)
                                        <div class="px-4 py-3 border border-black rounded borderbg-gray-200 ">
                                            <p class="mb-2 text-xs">
                                                <span
                                                    class="pr-2 font-bold">{{ $interview->interviewSchedule->getFormattedInterviewDate() }}</span>
                                                <span
                                                    class="pl-2 font-bold">{{ $interview->interviewSchedule->getFormattedInterviewStartTime() }}</span>
                                            </p>
                                            <p class="text-sm"><span class="font-bold">Interview number :</span>
                                                {{ $interview->id }}</p>
                                        </div>
                                    @else
                                        <div class="px-4 py-5 border border-black rounded borderbg-gray-200 ">
                                            <p>No interview schedule found.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <p class="mt-6 text-base"><span class="font-bold">Name of person in charge :</span>
                                {{ $interview->inchargeUser->name ?? 'N/A' }}
                            </p>
                            @if ($interview->interview_schedule_id)
                                <p class="mt-3 text-base"><span class="font-bold">Job offer number :</span>
                                    {{ $interview->interview_schedule_id }}</p>
                            @endif
                            @if ($interview->result)
                                <p class="mt-3 text-base"><span class="font-bold">Result :</span>
                                    @if ($interview->result === 'NotApplicable')
                                        Interview Not Conducted Yet
                                    @else
                                        {{ $interview->result }}
                                    @endif
                                </p>
                            @endif
                            @if ($interview->result_notification_date)
                                <p class="mt-3 text-base"><span class="font-bold">Result Published :</span>
                                    {{ $interview->result_notification_date->format('l jS \\of F Y') }}</p>
                            @endif
                            @if ($interview->reason)
                                <p class="mt-3 text-base"><span class="font-bold">Reason :</span>
                                    {{ $interview->reason }}</p>
                            @endif
                            @if ($interview->status === \App\Enum\InterviewStatus::INTERVIEW_CONFIRMED)
                                <div class="text-base">
                                    @if ($interview->zoom_link)
                                        <p class="mt-3"><span class="font-bold">Zoom Link:</span>
                                            <a href="{{ $interview->zoom_link }}" target="_blank"
                                                class="text-blue-600 underline hover:animate-pulse hover:font-bold">Click
                                                to Open Link</a>
                                            @if (Auth::user()->user_type !== 'Candidate')
                                                or
                                                <button wire:click="openZoomLinkModal" wire:loading.attr="disabled"
                                                    wire:loading.remove wire:target="openZoomLinkModal"
                                                    class="text-green-700 underline font-semibold hover:font-extrabold hover:animate-pulse hover:text-green-950">
                                                    Edit Zoom Link
                                                </button>
                                                <span wire:loading wire:target="openZoomLinkModal"
                                                    class="ml-1 font-bold text-green-700">
                                                    <i class="fa fa-spinner fa-spin"></i> Opening Zoom Link Form...
                                                </span>
                                                or
                                                <button wire:loading.attr="disabled" wire:loading.remove type="button"
                                                    wire:click="sendNotification"
                                                    class="ml-2 text-green-700 underline font-semibold hover:font-extrabold hover:animate-pulse hover:text-green-950">
                                                    Send Notification
                                                </button>
                                                <span wire:loading wire:target="sendNotification"
                                                    class="ml-1 font-bold text-green-700">
                                                    <i class="fa fa-spinner fa-spin"></i> Sending Mail to Candidate...
                                                </span>
                                            @endif
                                        </p>
                                    @elseif (Auth::user()->user_type == 'Candidate')
                                        <div class="mt-3 font-bold text-left text-md">Zoom Link</div>
                                        <div class="mt-3 text-sm font-semibold text-green-600">
                                            You will be notified by email once the zoom link is available. But for now
                                            save the
                                            schedule on your device.
                                        </div>
                                    @elseif (Auth::user()->user_type !== 'Candidate')
                                        <div class="mt-3 font-bold text-left text-md">Zoom Link</div>
                                        <div class="mt-3 text-sm font-semibold text-green-600">
                                            Please <button type="button" wire:click="openZoomLinkModal({{ $interview->id }})" class="underline animate-pulse hover:font-bold"><strong>Add a Zoom Link</strong></button> and save the schedule on your
                                            device.
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2 grid-cols-4 space-x-4">
                                    <p class="text-base grid-span-1 underline"><span class="font-bold">Click Icon to
                                            Save
                                            Schedule</span></p>
                                    <div class="flex">
                                        <a class="hover:animate-spin" href="{{ $this->getGoogleCalendarLink() }}"
                                            target="_blank" title="Add to Google Calendar">
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
                                        <a class="hover:animate-spin" href="{{ $this->getAppleCalendarLink() }}"
                                            target="_blank" title="Add to Apple Calendar">
                                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48"
                                                height="48" viewBox="0 0 24 24">
                                                <path
                                                    d="M 16.125 1 C 14.972 1.067 13.648328 1.7093438 12.861328 2.5273438 C 12.150328 3.2713438 11.589359 4.3763125 11.818359 5.4453125 C 13.071359 5.4783125 14.329031 4.8193281 15.082031 3.9863281 C 15.785031 3.2073281 16.318 2.12 16.125 1 z M 16.193359 5.4433594 C 14.384359 5.4433594 13.628 6.5546875 12.375 6.5546875 C 11.086 6.5546875 9.9076562 5.5136719 8.3476562 5.5136719 C 6.2256562 5.5146719 3 7.4803281 3 12.111328 C 3 16.324328 6.8176563 21 8.9726562 21 C 10.281656 21.013 10.599 20.176969 12.375 20.167969 C 14.153 20.154969 14.536656 21.011 15.847656 21 C 17.323656 20.989 18.476359 19.367031 19.318359 18.082031 C 19.922359 17.162031 20.170672 16.692344 20.638672 15.652344 C 17.165672 14.772344 16.474672 9.1716719 20.638672 8.0136719 C 19.852672 6.6726719 17.558359 5.4433594 16.193359 5.4433594 z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a class="hover:animate-spin" href="{{ $this->getOutlookCalendarLink() }}"
                                            target="_blank" title="Add to Outlook Calendar">
                                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48"
                                                height="48" viewBox="0 0 48 48">
                                                <path fill="#ff5722" d="M6 6H22V22H6z" transform="rotate(-180 14 14)">
                                                </path>
                                                <path fill="#4caf50" d="M26 6H42V22H26z" transform="rotate(-180 34 14)">
                                                </path>
                                                <path fill="#ffc107" d="M26 26H42V42H26z"
                                                    transform="rotate(-180 34 34)"></path>
                                                <path fill="#03a9f4" d="M6 26H22V42H6z" transform="rotate(-180 14 34)">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if (
                                $interview->employment_contract_procedure_application_date &&
                                    $interview->status->value === 'Employment application')
                                <p class="mt-3 text-base"><span class="font-bold">Employment Procedure Started:</span>
                                    {{ $interview->employment_contract_procedure_application_date->format('l jS \\of F Y') }}
                                </p>
                            @endif
                            {{-- @if (($interview->booking_request_date_student && Auth::user()->user_type == 'ComapanyAdmin') || Auth::user()->user_type == 'CompanyRepresentative')
                                <p class="mt-3 text-base"><span class="font-bold">This Interview had been Initiated:</span> By Candidate</p>
                            @endif
                            @if (($interview->booking_request_date_company && Auth::user()->user_type == 'CompanyAdmin') || Auth::user()->user_type == 'CompanyRepresentative')
                                <p class="mt-3 text-base"><span class="font-bold">This Interview had been Initiated:</span> By Company</p>
                            @endif --}}


                            {{-- this section will not show if the user_type is Candidate --}}
                            @if (Auth::user()->user_type !== 'Candidate')
                                <h3 class="mt-8 text-base font-semibold">Memo</h3>
                                <form wire:submit.prevent="saveMemo" class="mt-2">
                                    <div class="relative">
                                        <textarea wire:model.defer="memoContent" class="w-full h-24 p-2 resize-y"></textarea>
                                        <div class="absolute bottom-2 right-2">
                                            <button type="submit"
                                                class="relative px-4 py-1 mb-2 bg-white border border-black rounded hover:bg-gray-100">Save</button>
                                        </div>
                                    </div>
                                    @error('memoContent')
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </form>


                                <h3 class="mt-8 text-base font-semibold">Entry records</h3>
                                <ul class="mt-2 space-y-1 text-sm">
                                    @foreach ($interview->memos->sortByDesc('created_at') as $memo)
                                        <li><b>{{ $memo->user->name }}</b>, @if ($memo->user->user_type === 'CompanyAdmin')
                                                <i>Company Admin</i>
                                            @elseif ($memo->user->user_type === 'BusinessOperator')
                                                <i>Business Admin</i>
                                            @else
                                                <i>Company Representative</i>
                                            @endif : {{ $memo->content }}
                                            ({{ $memo->created_at->format('Y/m/d H:i') }})
                                        </li>
                                    @endforeach
                                    @if (!($interview->memos->count() > 0))
                                        <li>No entry records available</li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    @endif
                </div>
            </article>
            <nav
                class="flex gap-5 justify-between self-center mt-20 max-w-full text-sm text-center text-black w-[573px] max-md:flex-wrap max-md:mt-10">
                <a href="#" wire:click="returnToInterviewList"
                    class="justify-center px-8 py-6 bg-white border border-black border-solid rounded-md max-md:px-5">Return
                    to<br>List of Interview Status</a>
                <a href="#"
                    class="justify-center py-8 bg-white border border-black border-solid rounded-md px-11 max-md:px-5"
                    onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">Return to Top page</a>
            </nav>
        </div>
    </section>
    <!-- Zoom Link Modal -->
    @if ($showZoomLinkModal)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            @if (!$interview->zoom_link) Add Zoom Link @else Edit Zoom Link @endif
                        </h3>
                        <div class="mt-2">
                            <input type="text" wire:model.defer="zoomLink"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter Zoom link">
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="saveZoomLink" wire:loading.attr="disabled" wire:loading.remove
                            wire:target="saveZoomLink"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <div wire:loading wire:target="saveZoomLink">
                            <span class="font-bold text-indigo-700"><i class="fa fa-spinner fa-spin"></i> Saving...<span></span>
                        </div>
                        <button type="button" wire:click="closeZoomLinkModal" wire:loading.attr="disabled" wire:loading.remove
                            wire:target="closeZoomLink"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                        <div wire:loading wire:target="closeZoomLink">
                            <span class="font-bold text-gray-700"><i class="fa fa-spinner fa-spin"></i> Canceling...<span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
