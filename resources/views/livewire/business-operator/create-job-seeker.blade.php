<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-semibold mb-6">Create New Job Seeker</h2>

        <form wire:submit.prevent="createJobSeeker" enctype="multipart/form-data">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" wire:model="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" wire:model="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" wire:model="password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="name_kanji" class="block text-sm font-medium text-gray-700">Name (Kanji)</label>
                    <input type="text" id="name_kanji" wire:model="name_kanji" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('name_kanji') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="name_katakana" class="block text-sm font-medium text-gray-700">Name (Katakana)</label>
                    <input type="text" id="name_katakana" wire:model="name_katakana" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('name_katakana') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="name_japanese" class="block text-sm font-medium text-gray-700">Name (Japanese)</label>
                    <input type="text" id="name_japanese" wire:model="name_japanese" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('name_japanese') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="contact_phone_number" class="block text-sm font-medium text-gray-700">Contact Phone Number</label>
                    <input type="tel" id="contact_phone_number" wire:model="contact_phone_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('contact_phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select id="gender" wire:model="gender" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                    <input type="date" id="birth_date" wire:model="birth_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('birth_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
                    <select id="nationality" wire:model="nationality" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Nationality</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                    </select>
                    @error('nationality') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label for="last_education" class="block text-sm font-medium text-gray-700">Last Education</label>
                    <input type="text" id="last_education" wire:model="last_education" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('last_education') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label for="work_history" class="block text-sm font-medium text-gray-700">Work History</label>
                    <textarea id="work_history" wire:model="work_history" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    @error('work_history') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Qualifications</label>
                    <div class="mt-1 border border-gray-300 rounded-md p-4">
                        @foreach ($selectedQualificationsDetails as $qualification)
                            <div class="flex items-center justify-between mb-2">
                                <span>{{ $qualification->qualificationCategory->name }}: {{ $qualification->qualification_name }}</span>
                                <button type="button" wire:click="removeQualification({{ $qualification->id }})" class="text-red-500 hover:text-red-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                        <div class="mt-2">
                            <select wire:model.live="selectedCategory" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select Category</option>
                                @foreach ($qualificationCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($selectedCategory)
                            <div class="mt-2 flex">
                                <select wire:model.live="newQualification" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Select Qualification</option>
                                    @foreach ($qualificationsByCategory as $qualification)
                                        <option value="{{ $qualification->id }}">{{ $qualification->qualification_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" wire:click="addQualification" class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Add
                                </button>
                            </div>
                        @endif
                    </div>
                    @error('selectedQualifications') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label for="desired_job_type" class="block text-sm font-medium text-gray-700">Desired Job Type</label>
                    <select id="desired_job_type" wire:model="desired_job_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Desired Job Type</option>
                        @foreach($jobTypes as $jobType)
                            <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                        @endforeach
                    </select>
                    @error('desired_job_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label for="japanese_language_qualification" class="block text-sm font-medium text-gray-700">Japanese Language Qualification</label>
                    <input type="text" id="japanese_language_qualification" wire:model="japanese_language_qualification" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('japanese_language_qualification') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label for="self_presentation" class="block text-sm font-medium text-gray-700">Self Presentation</label>
                    <textarea id="self_presentation" wire:model="self_presentation" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    @error('self_presentation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2">
                    <label for="personal_preference" class="block text-sm font-medium text-gray-700">Personal Preference</label>
                    <textarea id="personal_preference" wire:model="personal_preference" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    @error('personal_preference') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-2 grid grid-cols-2 gap-4">
                    <div>
                        <label for="profileImage" class="block text-sm font-medium text-gray-700">Profile Image</label>
                        <div class="mt-1 relative w-32 h-32">
                            @if ($profileImage)
                                <img src="{{ $profileImage->temporaryUrl() }}" alt="Temp Profile Image"
                                    class="object-cover w-full h-full border border-gray-300 rounded-md shadow-sm">
                            @else
                                <img src="{{ asset('placeholder.png') }}" alt="Profile Image Placeholder"
                                    class="object-cover w-full h-full border border-gray-300 rounded-md shadow-sm">
                            @endif

                            <label for="profileImage"
                                class="absolute top-0 right-0 p-1 text-white bg-black bg-opacity-50 rounded-full cursor-pointer hover:bg-opacity-75">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <input type="file" id="profileImage" wire:model="profileImage" accept="image/*" class="hidden">
                            </label>
                        </div>
                        @error('profileImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="cvImage" class="block text-sm font-medium text-gray-700">CV Image</label>
                        <div class="mt-1 relative w-32 h-32">
                            @if ($cvImage)
                                <img src="{{ $cvImage->temporaryUrl() }}" alt="Temp CV Image"
                                    class="object-cover w-full h-full border border-gray-300 rounded-md shadow-sm">
                            @else
                                <img src="{{ asset('placeholder.png') }}" alt="CV Image Placeholder"
                                    class="object-cover w-full h-full border border-gray-300 rounded-md shadow-sm">
                            @endif

                            <label for="cvImage"
                                class="absolute top-0 right-0 p-1 text-white bg-black bg-opacity-50 rounded-full cursor-pointer hover:bg-opacity-75">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <input type="file" id="cvImage" wire:model="cvImage" accept="image/*" class="hidden">
                            </label>
                        </div>
                        @error('cvImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Job Seeker
                </button>
                <a href="{{ route('business-operator.job-seekers.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</div>
