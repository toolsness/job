<div>
    <div class="px-4 py-12 bg-white sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            @if ($confirmingApplication)
                <h1 class="mb-8 text-2xl font-bold text-center">Want to send this CV. Is this ok?</h1>
            @else
                <h1 class="mb-8 text-2xl font-bold text-center">Candidate Details</h1>
            @endif

            @if (session()->has('message'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                    role="alert">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                    role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col gap-8 lg:flex-row">
                <!-- Sidebar -->
                <aside class="w-full lg:w-1/4">
                    <div class="flex flex-col items-start text-black">
                        <div class="relative mb-4">
                            @if ($candidate->desiredJobType)
                                <div class="bg-orange-600 text-white text-sm px-4 py-1.5 rounded-sm mb-4 w-44">
                                    Special Skilled Worker: {{ ucfirst($candidate->desiredJobType->name) }}
                                </div>
                            @endif
                            <div class="relative w-44 h-44">
                                @if ($isUploading)
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-75 rounded">
                                        <svg class="w-12 h-12 text-gray-600 animate-spin"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                @if (($tempProfilePicture || $candidate->profile_picture_link) && $editing)
                                    <button type="button" title="Remove Profile Picture"
                                        wire:click="removeProfilePicture"
                                        class="absolute top-0 left-0 p-1 text-white bg-red-500 rounded-full hover:bg-red-600 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                @endif
                                @if ($tempProfilePicture)
                                    <img src="{{ $tempProfilePicture }}" alt="Temporary Profile Picture"
                                        class="object-cover rounded w-44 h-44">
                                @elseif ($candidate->profile_picture_link)
                                    <img src="{{ Storage::url($candidate->profile_picture_link) }}"
                                        alt="Profile Picture" class="object-cover rounded w-44 h-44">
                                @else
                                    <img src="https://via.placeholder.com/176" alt="Default Profile Picture"
                                        class="object-cover rounded w-44 h-44">
                                @endif
                            </div>
                            @if ($editing)
                                <div class="relative mt-2">
                                    <input type="file" wire:model.lazy="profilePicture" accept="image/*" class="sr-only"
                                        id="profile-picture-input">
                                    <label wire:loading.remove for="profile-picture-input"
                                        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25">
                                        Choose New Picture
                                    </label>
                                    <div wire:loading wire:target="profilePicture" class="mt-2 text-sm text-gray-600">
                                        Loading image...
                                    </div>
                                </div>
                                @error('profilePicture')
                                    <span class="block mt-1 text-xs text-red-500">{{ $message }}</span>
                                @enderror
                                <div x-data="{ progress: 0 }" x-on:livewire-upload-start="progress = 0"
                                    x-on:livewire-upload-finish="progress = 100"
                                    x-on:livewire-upload-error="progress = 0"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                                    <div x-show="progress > 0" class="w-full mt-2">
                                        <div class="flex justify-between mb-1">
                                            <span
                                                class="text-sm font-medium text-blue-700 dark:text-white">Uploading</span>
                                            <span class="text-sm font-medium text-blue-700 dark:text-white"
                                                x-text="`${progress}%`"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                            <div class="bg-blue-600 h-2.5 rounded-full" :style="`width: ${progress}%`">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <article class="w-full lg:w-3/4">
                    <form wire:submit.prevent="save">
                        <div class="space-y-6">
                            @foreach (['name', 'gender', 'birth_date', 'nationality', 'last_education', 'work_history', 'qualification', 'desired_job_type', 'self_presentation', 'personal_preference', 'publish_category'] as $field)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700">
                                        @if ($field === 'publish_category')
                                            <span class="font-extrabold">Would you allow interview request from
                                                company?</span>
                                        @else
                                            <span
                                                class="capitalize font-extrabold">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                                        @endif
                                    </label>
                                    @if ($editing)
                                        @if ($field === 'gender')
                                            <select id="{{ $field }}"
                                                wire:model.lazy="candidate.{{ $field }}"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        @elseif($field === 'birth_date')
                                            <input type="date" id="{{ $field }}"
                                                wire:model.lazy="candidate.{{ $field }}"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                        @elseif($field === 'nationality')
                                            <select id="{{ $field }}"
                                                wire:model.lazy="candidate.{{ $field }}"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->country_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif($field === 'desired_job_type')
                                            <select id="desired_job_type" wire:model.lazy="desiredJobType" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                <option value="">Select Desired Job Type</option>
                                                @foreach ($jobTypes as $jobType)
                                                    <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($field === 'qualification')
                                            <div class="mb-4">
                                                <div class="mt-1">
                                                    @foreach ($selectedQualifications as $index => $qualificationId)
                                                        <div class="flex items-center mb-2">
                                                            <span class="mr-2">
                                                                {{ $qualifications->firstWhere('id', $qualificationId)->qualificationCategory->name }}:
                                                                {{ $qualifications->firstWhere('id', $qualificationId)->qualification_name }}
                                                            </span>
                                                            <button type="button"
                                                                wire:click="removeQualification({{ $index }})"
                                                                class="text-red-500">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="flex mt-2">
                                                    <div class="w-full">
                                                        <select wire:model.live="selectedCategory"
                                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                            <option value="">Select Category</option>
                                                            @foreach ($qualificationCategories as $category)
                                                                <option value="{{ $category->id }}">
                                                                    {{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($selectedCategory)
                                                    <div class="flex mt-2">
                                                        <div class="w-full">
                                                            <select id="qualification-select"
                                                                wire:model.live="newQualification"
                                                                placeholder="Select Qualification">
                                                                <option value="">Select Qualification</option>
                                                                @foreach ($qualificationsByCategory as $qualification)
                                                                    <option value="{{ $qualification->id }}">
                                                                        {{ $qualification->qualification_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="button" wire:click="addQualification"
                                                            class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            Add
                                                        </button>
                                                    </div>
                                                @endif
                                                @error('selectedQualifications')
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @elseif(in_array($field, ['work_history', 'self_presentation', 'personal_preference']))
                                            <textarea id="{{ $field }}" wire:model.lazy="candidate.{{ $field }}"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                                        @elseif ($field === 'publish_category')
                                            <select id="{{ $field }}"
                                                wire:model.lazy="candidate.{{ $field }}"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                <option value="">Select</option>
                                                <option value="Published">Allow</option>
                                                <option value="NotPublished">Not Allow</option>
                                            </select>
                                        @else
                                            <input type="text" id="{{ $field }}"
                                                wire:model.lazy="candidate.{{ $field }}"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                        @endif
                                        @error('candidate.' . $field)
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    @else
                                        @if ($field === 'birth_date')
                                            <p class="mt-1 text-sm text-gray-900">
                                                {{ $candidate->birth_date ? $candidate->birth_date->format('Y-m-d') . ' (' . $candidate->birth_date->age . ' years old)' : '' }}
                                            </p>
                                        @elseif($field === 'nationality')
                                            <p class="mt-1 text-sm text-gray-900">
                                                {{ $candidate->country->country_name ?? '' }}</p>
                                        @elseif($field === 'desired_job_type')
                                            <p class="mt-1 text-sm text-gray-900">
                                                {{ $candidate->desiredJobType->name ?? '' }}</p>
                                        @elseif($field === 'qualification')
                                            <p class="mt-1 text-sm text-gray-900">
                                                @php
                                                    $groupedQualifications = $candidate->qualifications
                                                        ->sortBy('qualificationCategory.name')
                                                        ->groupBy('qualificationCategory.name');
                                                @endphp
                                                @foreach ($groupedQualifications as $categoryName => $qualifications)
                                                    <span class="font-bold">{{ $categoryName }}:</span><br>
                                                    @foreach ($qualifications->sortBy('qualification_name') as $qualification)
                                                        <span
                                                            class="ml-4 font-semibold">{{ $qualification->qualification_name }}</span><br>
                                                    @endforeach
                                                    <br>
                                                @endforeach
                                            </p>
                                        @elseif ($field === 'publish_category')
                                            <p class="mt-1 text-sm text-gray-900">
                                                {{ $candidate->publish_category === 'Published' ? 'Allow' : 'Not Allow' }}
                                            </p>
                                        @else
                                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $candidate->$field }}</p>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 text-center">
                            @if ($editing)
                                <div class="relative">
                                    <button wire:loading.remove type="submit"
                                        class="w-[300px] py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        wire:click="save" wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed">
                                        <span wire:target="save">
                                            @if ($vacancy)
                                                {{ $candidate->exists ? 'Update Details and Apply' : 'Create Profile and Apply' }}
                                            @else
                                                {{ $candidate->exists ? 'Update Details' : 'Create Profile' }}
                                            @endif
                                        </span>
                                    </button>
                                    <div wire:loading wire:target="save"
                                        class="items-center justify-center text-center">
                                        <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i>
                                            <span>{{ $candidate->exists ? ' Updating CV...' : ' Creating CV...' }}</span></span>
                                    </div>
                                </div>
                            @else
                                <button wire:loading.remove type="button" wire:click="toggleEdit"
                                    class="w-[300px] py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative"
                                    wire:loading.attr="disabled" wire:target="toggleEdit">
                                    Edit
                                </button>
                                <div wire:loading wire:target="toggleEdit"
                                    class="inline-flex items-center justify-center">
                                    <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                                            Opening Editing Form...</span></span>
                                </div>
                            @endif
                        </div>
                    </form>

                    @if ($confirmingApplication && $candidate->exists && !$editing)
                        <div class="mt-6 text-center">
                            <button wire:loading.remove wire:click="confirmApplication"
                                class="w-[300px] py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 relative"
                                wire:loading.attr="disabled" wire:target="confirmApplication">
                                <span wire:target="confirmApplication">
                                    Confirm and Apply for Interview
                                </span>
                            </button>
                            <div wire:loading wire:target="confirmApplication" class="inline-flex items-center">
                                <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                                        Processing...</span></span>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation -->
                    <nav class="flex flex-col justify-center gap-4 mt-12 sm:flex-row">
                        @if ($vacancy)
                            <a href="{{ route('job-details', $vacancy) }}"
                                class="px-6 py-3 text-sm text-center transition duration-300 bg-white border border-black rounded-md hover:bg-gray-100">
                                Back to Job Details
                            </a>
                        @endif
                        <a href="{{ route('home') }}"
                            class="px-6 py-3 text-sm text-center transition duration-300 bg-white border border-black rounded-md hover:bg-gray-100">
                            Return to Home
                        </a>
                    </nav>
                </article>
            </div>
        </div>
    </div>

    <!-- File Upload Progress Bar -->
    <div x-data="{ progress: 0 }" x-on:livewire-upload-start="progress = 0"
        x-on:livewire-upload-finish="progress = 100" x-on:livewire-upload-error="progress = 0"
        x-on:livewire-upload-progress="progress = $event.detail.progress">
        <div x-show="progress > 0" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4">
            <div class="bg-blue-600 h-2.5 rounded-full" :style="`width: ${progress}%`"></div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            initTomSelect();
        });

        function initTomSelect() {
            let selectElement = document.getElementById('qualification-select');
            if (selectElement) {
                new TomSelect(selectElement, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            }
        }

        Livewire.on('qualificationAdded', function() {
            let selectElement = document.getElementById('qualification-select');
            if (selectElement && selectElement.tomselect) {
                selectElement.tomselect.clear();
            }
        });

        Livewire.on('updatedQualifications', function() {
            let selectElement = document.getElementById('qualification-select');
            if (selectElement && selectElement.tomselect) {
                selectElement.tomselect.destroy();
            }
            initTomSelect();
        });
    </script>
</div>
