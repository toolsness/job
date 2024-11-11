<div class="px-4 py-12 bg-white sm:px-6 lg:px-8">
    <form wire:submit.prevent="save">
        <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-5">
                <div class="lg:col-span-4">
                    <div class="relative overflow-hidden rounded-lg">
                        @if ($vacancy)
                            @if ($editing)
                                <label for="image-upload" class="cursor-pointer">
                                    @if ($newImage)
                                        <img src="{{ $newImage->temporaryUrl() }}" alt="New job offer background image"
                                            class="object-cover w-full h-96">
                                    @else
                                        <img src="{{ $imageUrl }}" alt="Job offer background image"
                                            class="object-cover w-full h-96">
                                    @endif
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                        <span class="text-lg text-white">
                                            <span wire:loading.remove wire:target="newImage">
                                                Click to upload new image
                                            </span>
                                            <span wire:loading wire:target="newImage">
                                                <svg class="inline-block w-5 h-5 mr-3 -ml-1 text-white animate-spin"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                Uploading...
                                            </span>
                                        </span>
                                    </div>
                                </label>
                                <input id="image-upload" type="file" wire:model="newImage" class="hidden"
                                    accept="image/*">
                                @error('newImage')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            @else
                                <img src="{{ $imageUrl }}" alt="Job offer background image"
                                    class="object-cover w-full h-96">
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="px-4 py-2 text-sm text-black bg-orange-600 rounded-md">
                                    {{ $job_title }}
                                </span>
                            </div>
                        @else
                            <div class="flex items-center justify-center w-full bg-gray-200 h-96">
                                <span class="text-gray-500">No vacancy data available</span>
                            </div>
                        @endif
                    </div>

                </div>

                @if (!$isInterviewDetailsPage)
                    <div class="flex flex-col justify-end lg:col-span-1">
                        <div
                            class="relative @if ($userType === 'Student' || $userType === 'Candidate') border border-[#3AB2E3] border-x-4 border-y-4 p-2 @endif">
                            @if ($userType === 'Student' || $userType === 'Candidate')
                                <div class="absolute -top-4 -left-4 p-1">
                                    <button wire:click="toggleFavorite"
                                        class="text-2xl focus:outline-none bg-white rounded-full">
                                        @if ($isFavorite)
                                            <i class="fas fa-heart text-red-500" title="Remove from favorites"
                                                ></i>
                                        @else
                                            <i class="fa-sharp fa-regular fa-heart text-black"
                                                title="Add to favorites"></i>
                                        @endif
                                    </button>
                                </div>
                            @endif
                            <div class="flex flex-col gap-4 my-4">
                                @if ($userType === 'Student' || $userType === 'Candidate')
                                    <a href="#" wire:click.prevent="applyForInterview"
                                        class="w-full py-2 px-4 bg-[#FF0000] text-white font-bold text-sm border border-black rounded-md hover:bg-green-800 transition shadow-md">
                                        Apply for Interview
                                    </a>
                                @endif
                                <button type="button" wire:click.prevent="handleVrContentClick('Company Introduction')"
                                    class="w-full px-4 py-2 text-sm text-black transition bg-white border border-black rounded-md shadow-md hover:bg-gray-100">
                                    VR Company Information
                                </button>
                                <button type="button" wire:click.prevent="handleVrContentClick('Workplace Tour')"
                                    class="w-full px-4 py-2 text-sm text-black transition bg-white border border-black rounded-md shadow-md hover:bg-gray-100">
                                    VR Workplace Tour
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if ($vacancy)
                <p class="mt-4 text-xl text-black"><span class="font-semibold">Job offer
                        number：</span>{{ $vacancy->id }}</p>

                <div class="mt-12 space-y-6">
                    @foreach ([
        'Shop Name' => 'companyName',
        'Industry' => 'vacancy_category_id',
        'Job Title' => 'job_title',
        'Salary' => 'monthly_salary',
        'Job Location' => 'work_location',
        'Working Hours' => 'working_hours',
        'Transportation Expenses' => 'transportation_expenses',
        'Overtime pay' => 'overtime_pay',
        'Bonus' => 'salary_increase_and_bonuses',
        'Social insurance' => 'social_insurance',
        'Language Requirement' => 'japanese_language',
        'Other' => 'other_details',
    ] as $label => $field)
                        <div class="flex flex-col gap-2 text-xl text-black sm:flex-row">
                            <dt class="w-full font-semibold text-right sm:w-1/3">{{ $label }}：</dt>
                            <dd class="w-full sm:w-2/3">
                                @if ($editing && in_array($field, $editableFields))
                                    @if ($field === 'vacancy_category_id')
                                        <select wire:model.defer="{{ $field }}"
                                            class="w-full border-gray-300 rounded-md">
                                            @foreach ($vacancyCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    @elseif ($field === 'other_details')
                                        <textarea wire:model.defer="{{ $field }}" class="w-full border-gray-300 rounded-md"></textarea>
                                    @else
                                        <input type="text" wire:model.defer="{{ $field }}"
                                            class="w-full border-gray-300 rounded-md">
                                    @endif
                                    @error($field)
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                @else
                                    @if ($field === 'vacancy_category_id')
                                        {{ $vacancy->vacancyCategory?->name }}
                                    @elseif ($field === 'companyName')
                                        {{ $companyName }}
                                    @else
                                        {{ $vacancy->$field }}
                                    @endif
                                @endif
                            </dd>
                        </div>
                    @endforeach

                    <!-- VR Content Selection -->
                    @if (!in_array($userType, ['Student', 'Candidate']))
                        <div class="flex flex-col gap-2 text-xl text-black sm:flex-row">
                            <dt class="w-full font-semibold text-right sm:w-1/3">VR Company Introduction：</dt>
                            <dd class="w-full sm:w-2/3">
                                @if ($editing)
                                    <select wire:model.defer="vr_content_company_introduction_id"
                                        class="w-full border-gray-300 rounded-md">
                                        <option value="">Select VR Company Introduction</option>
                                        @foreach ($companyIntroductions as $intro)
                                            <option value="{{ $intro->id }}">
                                                {{ $intro->content_name }}
                                                (Status: {{ $intro->status }}, Last updated:
                                                {{ $intro->updated_at->format('Y-m-d H:i') }})
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    @if ($vacancy->vrContentCompanyIntroduction)
                                        {{ $vacancy->vrContentCompanyIntroduction->content_name }}
                                        <span class="text-sm text-gray-500">
                                            (Status: {{ $vacancy->vrContentCompanyIntroduction->status }},
                                            Last updated:
                                            {{ $vacancy->vrContentCompanyIntroduction->updated_at->format('Y-m-d H:i') }})
                                        </span>
                                    @else
                                        Not selected
                                    @endif
                                @endif
                            </dd>
                        </div>

                        <div class="flex flex-col gap-2 text-xl text-black sm:flex-row">
                            <dt class="w-full font-semibold text-right sm:w-1/3">VR Workplace Tour：</dt>
                            <dd class="w-full sm:w-2/3">
                                @if ($editing)
                                    <select wire:model.defer="vr_content_workplace_tour_id"
                                        class="w-full border-gray-300 rounded-md">
                                        <option value="">Select VR Workplace Tour</option>
                                        @foreach ($workplaceTours as $tour)
                                            <option value="{{ $tour->id }}">
                                                {{ $tour->content_name }}
                                                (Status: {{ $tour->status }}, Last updated:
                                                {{ $tour->updated_at->format('Y-m-d H:i') }})
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    @if ($vacancy->vrContentWorkplaceTour)
                                        {{ $vacancy->vrContentWorkplaceTour->content_name }}
                                        <span class="text-sm text-gray-500">
                                            (Status: {{ $vacancy->vrContentWorkplaceTour->status }},
                                            Last updated:
                                            {{ $vacancy->vrContentWorkplaceTour->updated_at->format('Y-m-d H:i') }})
                                        </span>
                                    @else
                                        Not selected
                                    @endif
                                @endif
                            </dd>
                        </div>
                    @endif

                    <!-- Status -->
                    <div class="flex flex-col gap-2 text-xl text-black sm:flex-row space-x-7">
                        <dt class="w-full font-semibold text-right sm:w-1/3">Status：</dt>
                        @if ($editing && in_array('publish_category', $editableFields))
                            <select wire:model.defer="publish_category" class="border-gray-300 rounded-md">
                                <option value="NotPublished">Not Published</option>
                                <option value="Published">Published</option>
                                <option value="PublicationStopped">Publication Stopped</option>
                            </select>
                            @error('publish_category')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        @else
                            <span
                                class="px-4 py-2 bg-white border border-black rounded-md opacity-70">{{ $publish_category }}</span>
                        @endif

                        @if (in_array($userType, ['CompanyAdmin', 'CompanyRepresentative']))
                            @if ($editing)
                                <button type="submit"
                                    class="px-6 py-2 text-black transition bg-white border border-black rounded-md text-md hover:bg-gray-100"
                                    wire:loading.attr="disabled" wire:target="save">
                                    <span wire:loading.remove wire:target="save">Save</span>
                                    <span wire:loading wire:target="save">
                                        <svg class="inline-block w-5 h-5 mr-3 -ml-1 text-black animate-spin"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Saving...
                                    </span>
                                </button>
                                <button type="button" wire:click="cancelEditing"
                                    class="px-6 py-2 text-sm text-black transition bg-white border border-black rounded-md hover:bg-gray-100">
                                    Cancel
                                </button>
                            @else
                                <button type="button" wire:click="toggleEditing"
                                    class="px-6 py-2 text-sm text-black transition bg-white border border-black rounded-md hover:bg-gray-100">
                                    Editing
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            @else
                <div class="mt-12 text-center text-gray-500">
                    No vacancy data available
                </div>
            @endif
        </div>
    </form>

    @if (session()->has('message'))
        <div class="p-4 mt-4 text-green-700 bg-green-100 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    <nav class="flex flex-col justify-center gap-4 mt-12 sm:flex-row">
        <a href="{{ route('job-list.search') }}"
            class="px-6 py-3 text-center text-black transition bg-white border border-black rounded-md text-md hover:bg-gray-100">
            Return to Job List
        </a>
        <a href="#"
            class="px-6 py-3 text-center text-black transition bg-white border border-black rounded-md text-md hover:bg-gray-100"
            onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">
            Return to TOP
        </a>
    </nav>

    <!-- Popup Modal -->
    <x-popup wire:model="showModal">
        <div>
            <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                    Notification
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500" x-text="$wire.modalMessage"></p>
                </div>
            </div>
        </div>
        <div class="mt-5 sm:mt-6">
            <button type="button" wire:click="closeModal"
                class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                OK
            </button>
        </div>
    </x-popup>
</div>
