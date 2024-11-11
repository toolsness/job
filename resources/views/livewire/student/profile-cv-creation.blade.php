<div class="flex justify-center items-center bg-gray-100 w-full max-w-4xl mx-auto rounded-lg shadow-md my-8">
    <div class="bg-white shadow-lg rounded-lg p-8 m-8 w-full">
        <!-- Title -->
        <h2 class="text-center text-2xl font-semibold mb-4">CV (Profile) Creation</h2>
        <p class="text-center text-gray-500 mb-6">Create a resume (CV) to submit to companies. <br>If you want to edit it
            later, go to Settings ➔ My Profile (CV).</p>

        <!-- Form -->
        <form wire:submit.prevent="save">
            <div class="space-y-2 mb-8">
                <!-- Name -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 text-center">Name</label>
                    <input type="text" class="border border-gray-500 p-2 w-3/4" wire:model="name">
                </div>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Gender -->
                <div class="flex items-center space-x-2 shadow-md">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 text-center">Gender</label>
                    <select class="border border-gray-500 p-2 w-3/4" wire:model="gender">
                        <option>--Choose an option--</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                @error('gender')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Date of Birth -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 text-center">Date
                        of Birth</label>
                    <input type="date" class="border border-gray-500 p-2 w-3/4" wire:model.lazy="birth_date">
                </div>
                @error('birth_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Age (disabled) -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 text-center">Age (Years)</label>
                    <input type="text" class="border border-gray-500 p-2 w-3/4 bg-gray-200" wire:model="age"
                        disabled>
                </div>

                <!-- Nationality -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 text-center">Nationality</label>
                    <select class="border border-gray-500 p-2 w-3/4" wire:model="nationality">
                        <option>--Choose an option--</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('nationality')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- College/University -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 text-center">College/University</label>
                    <input type="text" class="border border-gray-500 p-2 w-3/4" wire:model="college">
                </div>
                @error('college')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Work History -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 flex items-center justify-center text-center">
                        Work History
                    </label>
                    <textarea class="border border-gray-500 p-2 w-3/4 h-18 flex items-center" wire:model="work_history"></textarea>
                </div>
                @error('work_history')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Qualifications -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 text-center">Qualifications</label>
                    <div class="border border-gray-500 p-2 w-3/4">
                        <div class="mb-2">
                            @foreach ($selectedQualifications as $index => $qualificationId)
                                <div class="flex items-center mb-2">
                                    <span class="mr-2">
                                        {{ $qualifications->firstWhere('id', $qualificationId)->qualificationCategory->name }}:
                                        {{ $qualifications->firstWhere('id', $qualificationId)->qualification_name }}
                                    </span>
                                    <button type="button" wire:click="removeQualification({{ $index }})"
                                        class="text-red-500">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex mt-2">
                            <select wire:model.live="selectedCategory"
                                class="w-full p-2 border border-gray-300 rounded-md">
                                <option value="">Select Category</option>
                                @foreach ($qualificationCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($selectedCategory)
                            <div class="flex mt-2">
                                <select id="qualification-select" wire:model.live="newQualification"
                                    class="w-full p-2 border border-gray-300 rounded-md">
                                    <option value="">Select Qualification</option>
                                    @foreach ($qualificationsByCategory as $qualification)
                                        <option value="{{ $qualification->id }}">
                                            {{ $qualification->qualification_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" wire:click="addQualification"
                                    class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-md">
                                    Add
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                @error('selectedQualifications')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Japanese Language Qualifications -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold px-4 border border-gray-500 w-1/4 text-center">Japanese
                        Language Qualifications</label>
                    <input type="text" class="border border-gray-500 p-2 w-3/4" wire:model="japanese_language">
                </div>
                @error('japanese_language')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Desired Job Type -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 text-center">Desired
                        Job Type</label>
                    <select class="border border-gray-500 p-2 w-3/4" wire:model="desired_job_type">
                        <option>--Choose an option--</option>
                        @foreach ($jobTypes as $job_type)
                            <option value="{{ $job_type->id }}">{{ $job_type->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('desired_job_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Other Requests -->
                <div class="flex items-stretch space-x-2 shadow-lg">
                    <label
                        class="bg-blue-200 text-gray-700 font-semibold py-2 px-4 border border-gray-500 w-1/4 text-center">Other
                        Requests</label>
                    <input type="text" class="border border-gray-500 p-2 w-3/4" wire:model="other_request">
                </div>
                @error('other_request')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Publish Category -->
                <div class="flex items-center pt-10">
                    <label class="text-gray-700 py-2 px-4 w-2/5">Would you allow interview requests from companies?</label>
                    <select class="w-1/5 rounded shadow-lg" wire:model="publish_category">
                        <option value="Published">Published</option>
                        <option value="NotPublished">Not Published</option>
                        <option value="PublicationStopped">Publication Stopped</option>
                    </select>
                </div>
                @error('publish_category')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-around items-center mt-8">
                <!-- Back Button -->
                <a href="{{ route('interview-preparation-study-plan') }}"
                    class="flex items-center bg-gray-200 text-gray-800 hover:bg-gray-300 font-semibold py-2 px-4 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Interview Preparation
                </a>

                <!-- Create CV Button -->
                <button type="submit"
                    class="flex items-center bg-blue-500 text-white hover:bg-blue-600 font-semibold py-2 px-4 rounded-lg">
                    Create CV <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
        <div class="mt-16 text-center">
            <a href="{{ route('home') }}"
                class="px-4 py-2 text-gray-700 transition duration-300 bg-gray-200 rounded hover:bg-gray-300">Return to
                TOP page</a>
        </div>
    </div>
</div>
