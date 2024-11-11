<div class="container mx-auto px-4 py-8" x-data="{ showDeleteConfirmation: @entangle('showDeleteConfirmation') }">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-6">Job Seeker Details</h2>

        <div class="flex flex-col md:flex-row">
            <!-- Left column for image -->
            <div class="md:w-1/5 mb-4 md:mb-0 relative">
                @if (!$isEditing)
                    <div class="pb-4">
                        <div
                            class="w-full h-8 bg-orange-600 text-white flex justify-center items-center text-xs rounded-md">
                            {{ $jobTypes->firstWhere('id', $desired_job_type)->name ?? '' }}
                        </div>
                    </div>
                @endif
                @if ($tempCvImage)
                    <img src="{{ $tempCvImage->temporaryUrl() }}" alt="{{ $name }}"
                        class="w-full h-auto object-cover mb-4">
                    @if ($isEditing)
                        <button wire:click="deleteTempCvImage"
                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 text-white p-1 rounded-full"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </span>
                            <span wire:loading wire:target="deleteTempCvImage">
                                <i class="fa fa-spinner fa-spin"></i> Deleting...
                            </span>
                        </button>
                    @endif
                @elseif ($cvImage)
                    <img src="{{ Storage::url($cvImage) }}" alt="{{ $name }}"
                        class="w-full h-auto object-cover mb-4">
                    @if ($isEditing)
                        <button wire:click="deleteCvImage"
                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 text-white p-1 rounded-full"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </span>
                            <span wire:loading wire:target="deleteCvImage">
                                <i class="fa fa-spinner fa-spin"></i> Deleting...
                            </span>
                        </button>
                    @endif
                @else
                    <div class="w-full h-48 bg-gray-200 rounded-md"></div>
                @endif

                @if ($isEditing)
                    <div wire:loading wire:target="tempCvImage" class="mt-1">
                        <span class="font-bold text-green-500"><i class="fa fa-spinner fa-spin"></i> Uploading...</span>
                    </div>
                    <input type="file" wire:model="tempCvImage" id="tempCvImage"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        wire:loading.attr="disabled">
                    @error('tempCvImage')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                @endif
            </div>

            <!-- Right column for form fields -->
            <div class="md:w-4/5 md:pl-6">
                @if (!$isEditing)
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $fields = [
                                'User ID' => $jobSeeker->student->user->username,
                                'Name' => $name,
                                'Gender' => $gender,
                                'Date of Birth' => $birth_date,
                                'Age' => \Carbon\Carbon::parse($birth_date)->age . ' years old',
                                'Country of origin' => $countries->firstWhere('id', $nationality)->country_name ?? '',
                                'Email Address' => $email,
                                'Contact' => $contact_phone_number,
                                'Last educational background' => $last_education,
                                'Work experience' => $work_history,
                                'Qualifications' => $selectedQualificationsDetails
                                    ->pluck('qualification_name')
                                    ->map(function ($item) {
                                        return ucfirst(strtolower(html_entity_decode($item)));
                                    })
                                    ->implode(', '),
                                'Japanese Language Qualification' => $japanese_language_qualification,
                                'Desired Job Type' => $jobTypes->firstWhere('id', $desired_job_type)->name ?? '',
                                'Self-publicity' => $self_presentation,
                                "Person's wishes" => $personal_preference,
                                'Other Request' => $other_request,
                                'Status' =>
                                    $publish_category == 'Published'
                                        ? 'Published'
                                        : ($publish_category == 'NotPublished'
                                            ? 'Not Published'
                                            : 'Publication Stopped'),
                            ];
                        @endphp
                        @foreach ($fields as $label => $value)
                            <div class="text-right font-bold">{{ $label }}:</div>
                            <div>{{ $value }}</div>
                        @endforeach
                    </div>

                    <div class="flex justify-between mt-6">
                        <button wire:click="startEditing"
                            class="bg-white hover:bg-orange-500 text-black hover:text-black font-bold py-2 px-4 rounded border border-black">
                            Edit Job Seeker
                        </button>
                        <button wire:click="confirmDelete"
                            class="hover:bg-red-500 bg-white text-black hover:text-white font-bold py-2 px-4 rounded border border-black">
                            Delete Job Seeker
                        </button>
                    </div>
                @else
                    <form wire:submit.prevent="updateJobSeeker">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="name" wire:model="name"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" wire:model="email"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select id="gender" wire:model="gender"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('gender')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth
                                    Date</label>
                                <input type="date" id="birth_date" wire:model="birth_date"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('birth_date')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="nationality"
                                    class="block text-sm font-medium text-gray-700">Nationality</label>
                                <select id="nationality" wire:model="nationality"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Nationality</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                @error('nationality')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="last_education" class="block text-sm font-medium text-gray-700">Last
                                    Education</label>
                                <input type="text" id="last_education" wire:model="last_education"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('last_education')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="work_history" class="block text-sm font-medium text-gray-700">Work
                                    History</label>
                                <textarea id="work_history" wire:model="work_history" rows="3"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                @error('work_history')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Qualifications</label>
                                <div class="mt-1 border border-gray-300 rounded-md p-4">
                                    @foreach ($selectedQualificationsDetails as $qualification)
                                        <div class="flex items-center justify-between mb-2">
                                            <span>{{ $qualification->qualificationCategory->name }}:
                                                {{ $qualification->qualification_name }}</span>
                                            <button type="button"
                                                wire:click="removeQualification({{ $qualification->id }})"
                                                class="text-red-500 hover:text-red-700">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                    <div class="mt-2">
                                        <select wire:model.live="selectedCategory"
                                            class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">Select Category</option>
                                            @foreach ($qualificationCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($selectedCategory)
                                        <div class="mt-2 flex">
                                            <select wire:model.live="newQualification"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">Select Qualification</option>
                                                @foreach ($qualificationsByCategory as $qualification)
                                                    <option value="{{ $qualification->id }}">
                                                        {{ $qualification->qualification_name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" wire:click="addQualification"
                                                class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Add
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                @error('selectedQualifications')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="japanese_language_qualification"
                                    class="block text-sm font-medium text-gray-700">Japanese Language
                                    Qualification</label>
                                <input type="text" id="japanese_language_qualification"
                                    wire:model="japanese_language_qualification"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('japanese_language_qualification')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="desired_job_type" class="block text-sm font-medium text-gray-700">Desired
                                    Job Type</label>
                                <select id="desired_job_type" wire:model="desired_job_type"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Desired Job Type</option>
                                    @foreach ($jobTypes as $jobType)
                                        <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                    @endforeach
                                </select>
                                @error('desired_job_type')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="self_presentation" class="block text-sm font-medium text-gray-700">Self
                                    Presentation</label>
                                <textarea id="self_presentation" wire:model="self_presentation" rows="3"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                @error('self_presentation')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="personal_preference"
                                    class="block text-sm font-medium text-gray-700">Personal Preference</label>
                                <textarea id="personal_preference" wire:model="personal_preference" rows="3"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                @error('personal_preference')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="other_request" class="block text-sm font-medium text-gray-700">Other
                                    Request</label>
                                <textarea id="other_request" wire:model="other_request" rows="3"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                @error('other_request')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="publish_category"
                                    class="block text-sm font-medium text-gray-700">Publication Status</label>
                                <select id="publish_category" wire:model="publish_category"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="NotPublished">Not Published</option>
                                    <option value="Published">Published</option>
                                    <option value="PublicationStopped">Publication Stopped</option>
                                </select>
                                @error('publish_category')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Changes
                            </button>
                            <button type="button" wire:click="cancelEditing"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('business-operator.job-seekers.index') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Back to Job Seekers List
        </a>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-popup wire:model="showDeleteConfirmation">
        <div class="p-6">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Job Seeker</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this job seeker? This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="deleteJobSeeker"
                        class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Delete
                    </button>
                    <button wire:click="cancelDelete"
                        class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </x-popup>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('jobSeekerUpdated', function() {
                // You can add any JavaScript functionality here that should run after the job seeker is updated
                // For example, you could show a success message or refresh some part of the page
            });
        });
    </script>
</div>
