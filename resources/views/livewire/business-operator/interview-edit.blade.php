<div class="container mx-auto mt-8">
    <div class="container mx-auto mt-8 grid grid-cols-3 gap-4">

        <div class="w-1/5"></div>
        <div class="w-3/5 col-span-2">

            <h2 class="text-2xl font-bold mb-4">Edit Interview</h2>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (!$isEditing)
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Company:</label>
                        <p>{{ $interview->vacancy->company->name }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Vacancy:</label>
                        <p>{{ $interview->vacancy->job_title }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Interviewer:</label>
                        <p>{{ $interview->inchargeUser->name ?? '' }}
                            ({{ $interview->inchargeUser->user_type ?? 'Please select' }})</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Candidate:</label>
                        <p>{{ $interview->candidate->name }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Interview Date:</label>
                        <p>{{ $interview->implementation_date ? $interview->implementation_date->format('Y-m-d') : 'N/A' }}
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Interview Time:</label>
                        <p>{{ $interview->implementation_start_time ? $interview->implementation_start_time->format('H:i') : 'N/A' }}
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                        <p>{{ $interview->status->getDisplayText() }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Zoom Link:</label>
                        <p>{{ $interview->zoom_link ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Interview Result:</label>
                        <p>{{ $interview->result }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Employment Contract Procedure
                            Application Date:</label>
                        <p>{{ $interview->employment_contract_procedure_application_date?->format('Y-m-d') ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Booking Request Date
                            (Student):</label>
                        <p>{{ $interview->booking_request_date_student?->format('Y-m-d') ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Booking Request Date
                            (Company):</label>
                        <p>{{ $interview->booking_request_date_company?->format('Y-m-d') ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Booking Confirmation Date:</label>
                        <p>{{ $interview->booking_confirmation_date?->format('Y-m-d') ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Result Notification Date:</label>
                        <p>{{ $interview->result_notification_date?->format('Y-m-d') ?? 'N/A' }}</p>
                    </div>
                    <div class="mt-4">
                        <h4 class="text-md font-semibold mb-2">Existing Memos</h4>
                        @forelse ($memos as $memo)
                            <div class="mb-2 p-2 border rounded">
                                <p class="text-sm">{{ $memo->content }}</p>
                                <p class="text-xs text-gray-500">By {{ $memo->user->name }} on
                                    {{ $memo->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        @empty
                            <p>No memos yet.</p>
                        @endforelse
                    </div>
                    <div class="flex items-center justify-between mt-4 mb-4">
                        <button wire:click="startEditing"
                            class="bg-white hover:bg-orange-500 text-black hover:text-black font-bold py-2 px-4 rounded border border-black focus:outline-none focus:shadow-outline">
                            Edit
                        </button>

                        
                            <button wire:loading.remove wire:click="performNotificationSend" type="button"
                                class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                wire:remo>
                                Send Zoom Link Notification
                            </button>
                            <div wire:loading wire:target="performNotificationSend"
                                class="inline-flex items-center justify-center">
                                <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                                        Sending Notification Message and Mail to the Candidate and Incharge
                                        Person...</span></span>
                            </div>


                        <button wire:click="confirmDelete"
                            class="hover:bg-red-500 bg-white text-black hover:text-white font-bold py-2 px-4 rounded border border-black focus:outline-none focus:shadow-outline">
                            Delete
                        </button>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="updateInterview">
                    <div class="mb-4">
                        <label for="company_id" class="block text-gray-700 text-sm font-bold mb-2">Company:</label>
                        <select wire:model.live="company_id" id="company_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-400 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 cursor-not-allowed"
                            disabled>
                            <option value="">Select a company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="vacancy_id" class="block text-gray-700 text-sm font-bold mb-2">Vacancy:</label>
                        <select wire:model.live="vacancy_id" id="vacancy_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-400 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 cursor-not-allowed"
                            disabled>
                            <option value="">Select a vacancy</option>
                            @foreach ($vacancies as $vacancy)
                                <option value="{{ $vacancy->id }}">{{ $vacancy->job_title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="incharge_user_id" class="block text-gray-700 text-sm font-bold mb-2">Select
                            Interviewer:</label>
                        <select wire:model.live="incharge_user_id" id="incharge_user_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select an in-charge user</option>
                            @foreach ($inchargeUsers as $user)
                                <option value="{{ $user['id'] }}">{{ $user['name'] }} ({{ $user['type'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('incharge_user_id')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="candidate_id" class="block text-gray-700 text-sm font-bold mb-2">Candidate:</label>
                        <select wire:model.live="candidate_id" id="candidate_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-400 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 cursor-not-allowed"
                            disabled>
                            @foreach ($candidates as $candidate)
                                <option value="{{ $candidate->id }}">{{ $candidate->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="implementation_date" class="block text-gray-700 text-sm font-bold mb-2">Interview
                            Date:</label>
                        <input wire:model="implementation_date" type="date" id="implementation_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('implementation_date')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="implementation_start_time"
                            class="block text-gray-700 text-sm font-bold mb-2">Interview Time:</label>
                        <input wire:model="implementation_start_time" type="time" id="implementation_start_time"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('implementation_start_time')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                        <select wire:model="status" id="status"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @foreach ($statuses as $statusOption)
                                <option value="{{ $statusOption['value'] }}">{{ $statusOption['label'] }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="zoom_link" class="block text-gray-700 text-sm font-bold mb-2">Zoom Link:</label>
                        <div class="flex items-center">
                            <input wire:model="zoom_link" type="url" id="zoom_link"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        @error('zoom_link')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="result" class="block text-gray-700 text-sm font-bold mb-2">Interview
                            Result:</label>
                        <select wire:model="result" id="result"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-400 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 cursor-not-allowed"
                            disabled>
                            <option value="">Select Result</option>
                            @foreach ($results as $resultOption)
                                <option value="{{ $resultOption }}">{{ $resultOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="employment_contract_procedure_application_date"
                            class="block text-gray-700 text-sm font-bold mb-2">Employment Contract Procedure
                            Application Date:</label>
                        <input type="date" id="employment_contract_procedure_application_date"
                            wire:model="employment_contract_procedure_application_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-400 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>

                    <div class="mb-4">
                        <label for="booking_request_date_student"
                            class="block text-gray-700 text-sm font-bold mb-2">Booking Request Date (Student):</label>
                        <input type="date" id="booking_request_date_student"
                            wire:model="booking_request_date_student"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-400 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>

                    <div class="mb-4">
                        <label for="booking_request_date_company"
                            class="block text-gray-700 text-sm font-bold mb-2">Booking Request Date (Company):</label>
                        <input type="date" id="booking_request_date_company"
                            wire:model="booking_request_date_company"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-400 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>

                    <div class="mb-4">
                        <label for="booking_confirmation_date"
                            class="block text-gray-700 text-sm font-bold mb-2">Booking Confirmation Date:</label>
                        <input type="date" id="booking_confirmation_date" wire:model="booking_confirmation_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-400 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>

                    <div class="mb-4">
                        <label for="result_notification_date"
                            class="block text-gray-700 text-sm font-bold mb-2">Result Notification Date:</label>
                        <input type="date" id="result_notification_date" wire:model="result_notification_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-400 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-2">Memos</h3>
                        <form wire:submit.prevent="saveMemo">
                            <div class="relative">
                                <textarea wire:model="memoContent" rows="4"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-100"
                                    disabled></textarea>
                                <div class="absolute bottom-2 left-2">
                                    <button type="submit"
                                        class="relative px-4 py-1 mb-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded focus:outline-none focus:shadow-outline"
                                        disabled>Add
                                        Memo</button>
                                </div>
                                @error('memoContent')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="mt-4">
                        <h4 class="text-md font-semibold mb-2">Existing Memos</h4>
                        @forelse ($memos as $memo)
                            <div class="mb-2 p-2 border rounded">
                                <p class="text-sm">{{ $memo->content }}</p>
                                <p class="text-xs text-gray-500">By {{ $memo->user->name }} on
                                    {{ $memo->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        @empty
                            <p>No memos yet.</p>
                        @endforelse
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <button type="submit" wire:click="updateInterview"
                            class="bg-white text-black border border-black hover:bg-green-700 hover:text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Interview
                        </button>
                        <button wire:click="cancelEditing" type="button"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </button>
                    </div>
                </form>
            @endif

            @if ($showDeleteConfirmation)
                <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                    aria-modal="true">
                    <div
                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true">
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>
                        <div
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Delete Interview
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Are you sure you want to delete this interview? This action cannot be
                                                undone.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button wire:click="deleteInterview" type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Delete
                                </button>
                                <button wire:click="cancelDelete" type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="w-1/5 relative"></div>

    </div>
    <div class="mt-8 pb-4 text-center">
        <a href="{{ route('business-operator.interviews.index') }}"
            class="bg-white hover:bg-green-700 text-black hover:text-white font-bold py-2 px-4 rounded border border-black focus:outline-none focus:shadow-outline">
            Back to Interview List
        </a>
    </div>

    <div class="mt-4 text-center pb-6">
        <a href="{{ route('home') }}"
            class="bg-white hover:bg-green-700 text-black hover:text-white font-bold py-2 px-4 rounded border border-black focus:outline-none focus:shadow-outline">
            Return to TOP
        </a>
    </div>

    <!-- Zoom Link Modal -->
    @if ($showZoomLinkModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Save Zoom Link
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Please enter the Zoom link for this interview:
                            </p>
                            <input type="url" wire:model.defer="tempZoomLink" class="mt-2 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="https://zoom.us/j/example">
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveZoomLink" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save and Send Notification
                        </button>
                        <button wire:click="cancelZoomLinkSave" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
