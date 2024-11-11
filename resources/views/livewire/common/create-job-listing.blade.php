<div>
    <div class="bg-white py-12 px-4 sm:px-6 lg:px-8">
        <form wire:submit.prevent="save">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold mb-4 text-center">Create New Vacancy</h2>

                @if (session()->has('message'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <!-- Image upload section -->
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                    <div class="lg:col-span-5">
                        <div class="relative rounded-lg overflow-hidden">
                            <label for="image-upload" class="cursor-pointer">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" alt="New job offer background image"
                                        class="w-full h-96 object-cover">
                                @else
                                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500">Click to upload an image</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <span class="text-white text-lg">
                                        <span wire:loading.remove wire:target="image">
                                            Click to upload new image
                                        </span>
                                        <span wire:loading wire:target="image">
                                            <svg class="animate-spin h-5 w-5 mr-3 inline-block text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                            <input id="image-upload" type="file" wire:model="image" class="hidden" accept="image/*">
                        </div>
                        @error('image')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-12 space-y-6">
                    <!-- Company Name -->
                    <div class="flex flex-col sm:flex-row gap-2 text-xl text-black">
                        <dt class="w-full sm:w-1/3 text-right font-semibold">Company Name：</dt>
                        <dd class="w-full sm:w-2/3">
                            <span class="w-full border-gray-300 rounded-md">{{ $companyName }}</span>
                        </dd>
                    </div>

                    <!-- Job Industry -->
                    <div class="flex flex-col sm:flex-row gap-2 text-xl text-black">
                        <dt class="w-full sm:w-1/3 text-right font-semibold">Job Industry：</dt>
                        <dd class="w-full sm:w-2/3">
                            <select wire:model="vacancy_category_id" class="w-full border-gray-300 rounded-md">
                                <option value="">Select Job Industry</option>
                                @foreach ($vacancyCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('vacancy_category_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </dd>
                    </div>

                    <!-- Other form fields -->
                    @foreach ([
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
                        <div class="flex flex-col sm:flex-row gap-2 text-xl text-black">
                            <dt class="w-full sm:w-1/3 text-right font-semibold">{{ $label }}：</dt>
                            <dd class="w-full sm:w-2/3">
                                @if ($field === 'other_details')
                                    <textarea wire:model.lazy="{{ $field }}" class="w-full border-gray-300 rounded-md" placeholder="Enter any additional information about the job (e.g., benefits, requirements, etc.)"></textarea>
                                @elseif ($field === 'job_title')
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="Software Engineer">
                                @elseif ($field === 'monthly_salary')
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="¥300,000">
                                @elseif ($field === 'work_location')
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="Tokyo, Osaka, etc.">
                                @elseif ($field === 'working_hours')
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="9:00 AM - 5:00 PM">
                                @elseif ($field === 'transportation_expenses')
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="Up to ¥10,000 per month">
                                @elseif ($field === 'overtime_pay')
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="1.25x hourly rate">
                                @elseif ($field === 'salary_increase_and_bonuses')
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="Annual performance-based bonuses">
                                @elseif ($field === 'japanese_language')
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="N1, N2, Business Level, etc.">
                                @elseif ($field === 'social_insurance')
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="Provided by employer">
                                @else
                                    <input type="text" wire:model.lazy="{{ $field }}"
                                        class="w-full border-gray-300 rounded-md" placeholder="Enter information here...">
                                @endif
                                @error($field)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </dd>
                        </div>
                    @endforeach

                    <!-- VR Content Selection -->
                <div class="mt-6 space-y-6">
                    <div class="flex flex-col sm:flex-row gap-2 text-xl text-black">
                        <dt class="w-full sm:w-1/3 text-right font-semibold">VR Company Introduction：</dt>
                        <dd class="w-full sm:w-2/3">
                            <select wire:model="vr_content_company_introduction_id" class="w-full border-gray-300 rounded-md">
                                <option value="">Select VR Company Introduction</option>
                                @foreach($companyIntroductions as $intro)
                                    <option value="{{ $intro->id }}">{{ $intro->content_name }}</option>
                                @endforeach
                            </select>
                            @error('vr_content_company_introduction_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </dd>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 text-xl text-black">
                        <dt class="w-full sm:w-1/3 text-right font-semibold">VR Workplace Tour：</dt>
                        <dd class="w-full sm:w-2/3">
                            <select wire:model="vr_content_workplace_tour_id" class="w-full border-gray-300 rounded-md">
                                <option value="">Select VR Workplace Tour</option>
                                @foreach($workplaceTours as $tour)
                                    <option value="{{ $tour->id }}">{{ $tour->content_name }}</option>
                                @endforeach
                            </select>
                            @error('vr_content_workplace_tour_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </dd>
                    </div>
                </div>

                    <!-- Status -->
                    <div class="flex flex-col sm:flex-row gap-2 text-xl text-black">
                        <dt class="w-full sm:w-1/3 text-right font-semibold">Status：</dt>
                        <dd class="w-full sm:w-2/3">
                            <select wire:model="publish_category" class="w-full border-gray-300 rounded-md">
                                <option value="NotPublished">Not Published</option>
                                <option value="Published">Published</option>
                                <option value="PublicationStopped">Publication Stopped</option>
                            </select>
                            @error('publish_category')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </dd>
                    </div>
                </div>



                <div class="mt-8 flex justify-center">
                    <button type="submit"
                        class="px-6 py-2 hover:bg-green-600 hover:text-white text-lg font-semibold rounded-md bg-white text-black border border-black transition"
                        wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">Create Vacancy</span>
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-5 w-5 mr-3 inline-block" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Creating New Vacancy...
                        </span>
                    </button>
                </div>
            </div>
        </form>

        <nav class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('job-list.search') }}"
                class="px-6 py-3 bg-white text-black text-md text-center border border-black rounded-md hover:bg-gray-100 transition">
                Return to Job List
            </a>
        </nav>
    </div>
</div>
