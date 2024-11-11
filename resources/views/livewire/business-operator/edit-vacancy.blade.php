<div class="container mx-auto px-4 py-8" x-data="{ showDeleteConfirmation: @entangle('showDeleteConfirmation') }">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-6">Edit Vacancy</h2>

        @if (!$isEditing)
            <div class="mb-4">
                @if ($image)
                    <img src="{{ Storage::url($image) }}" alt="{{ $job_title }}" class="w-32 h-32 object-cover mb-4">
                @endif
                <p><strong>Job Title:</strong> {{ $job_title }}</p>
                <p><strong>Publish Category:</strong> {{ $publish_category }}</p>
                <p><strong>Company:</strong> {{ $vacancy->company->name }}</p>
                <p><strong>Monthly Salary:</strong> {{ $monthly_salary }}</p>
                <p><strong>Work Location:</strong> {{ $work_location }}</p>
                <p><strong>Working Hours:</strong> {{ $working_hours }}</p>
                <p><strong>Transportation Expenses:</strong> {{ $transportation_expenses }}</p>
                <p><strong>Overtime Pay:</strong> {{ $overtime_pay }}</p>
                <p><strong>Salary Increase and Bonuses:</strong> {{ $salary_increase_and_bonuses }}</p>
                <p><strong>Social Insurance:</strong> {{ $social_insurance }}</p>
                <p><strong>Japanese Language Requirement:</strong> {{ $japanese_language }}</p>
                <p><strong>Other Details:</strong> {{ $other_details }}</p>
                <p><strong>Vacancy Category:</strong> {{ $vacancy->vacancyCategory->name }}</p>
                <p><strong>VR Content - Company Introduction:</strong>
                    {{ $vacancy->vrContentCompanyIntroduction->content_name ?? 'N/A' }}</p>
                <p><strong>VR Content - Workplace Tour:</strong>
                    {{ $vacancy->vrContentWorkplaceTour->content_name ?? 'N/A' }}</p>
            </div>

            <div class="flex justify-between">
                <button wire:click="$set('isEditing', true)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </button>
                <button wire:click="$set('showDeleteConfirmation', true)"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </div>
        @else
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="company_id">
                        Company
                    </label>
                    <select wire:model.live="company_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="company_id">
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                @if ($selectedCompany)
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="user_type">
                            User Type
                        </label>
                        <select wire:model.live="user_type"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="user_type">
                            <option value="">Select user type</option>
                            <option value="CompanyAdmin" @selected($user_type === 'CompanyAdmin')>
                                Company Admin ({{ $selectedCompany->companyAdmins->count() }})
                            </option>
                            <option value="CompanyRepresentative" @selected($user_type === 'CompanyRepresentative')>
                                Company Representative ({{ $selectedCompany->companyRepresentatives->count() }})
                            </option>
                        </select>
                        @error('user_type')
                            <span class="text-red-500 text-xs italic">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($user_type)
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="selected_user_id">
                                Select User
                            </label>
                            <select wire:model.live="selected_user_id"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="selected_user_id">
                                <option value="">Select a user</option>
                                @foreach ($companyUsers as $user)
                                    <option value="{{ $user->user_id }}" @selected($selected_user_id == $user->user_id)>
                                        {{ $user->user->name }} ({{ $user_type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('selected_user_id')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                @endif

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="publish_category">
                        Publish Category
                    </label>
                    <select wire:model="publish_category"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="publish_category">
                        <option value="">Select a category</option>
                        <option value="NotPublished">Not Published</option>
                        <option value="Published">Published</option>
                        <option value="PublicationStopped">Publication Stopped</option>
                    </select>
                    @error('publish_category')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="job_title">
                        Job Title
                    </label>
                    <input wire:model="job_title"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="job_title" type="text">
                    @error('job_title')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="monthly_salary">
                        Monthly Salary
                    </label>
                    <input wire:model="monthly_salary"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="monthly_salary" type="text">
                    @error('monthly_salary')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="work_location">
                        Work Location
                    </label>
                    <input wire:model="work_location"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="work_location" type="text">
                    @error('work_location')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="working_hours">
                        Working Hours
                    </label>
                    <input wire:model="working_hours"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="working_hours" type="text">
                    @error('working_hours')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="transportation_expenses">
                        Transportation Expenses
                    </label>
                    <input wire:model="transportation_expenses"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="transportation_expenses" type="text">
                    @error('transportation_expenses')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="overtime_pay">
                        Overtime Pay
                    </label>
                    <input wire:model="overtime_pay"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="overtime_pay" type="text">
                    @error('overtime_pay')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="salary_increase_and_bonuses">
                        Salary Increase and Bonuses
                    </label>
                    <input wire:model="salary_increase_and_bonuses"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="salary_increase_and_bonuses" type="text">
                    @error('salary_increase_and_bonuses')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="social_insurance">
                        Social Insurance
                    </label>
                    <input wire:model="social_insurance"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="social_insurance" type="text">
                    @error('social_insurance')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="japanese_language">
                        Japanese Language Requirement
                    </label>
                    <input wire:model="japanese_language"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="japanese_language" type="text">
                    @error('japanese_language')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="other_details">
                        Other Details
                    </label>
                    <textarea wire:model="other_details"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="other_details"></textarea>
                    @error('other_details')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="vacancy_category_id">
                        Vacancy Category
                    </label>
                    <select wire:model="vacancy_category_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="vacancy_category_id">
                        @foreach ($vacancyCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('vacancy_category_id')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tempImage">
                        Image
                    </label>
                    @if ($image)
                        <img src="{{ Storage::url($image) }}" alt="{{ $job_title }}"
                            class="w-32 h-32 object-cover mb-2">
                        <button type="button" wire:click="$set('image', null)"
                            class="text-red-500 hover:text-red-700">
                            Delete Image
                        </button>
                    @endif
                    <input wire:model="tempImage"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="tempImage" type="file">
                    @error('tempImage')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                @if ($selectedCompany)
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2"
                            for="vr_content_company_introduction_id">
                            VR Content - Company Introduction
                        </label>
                        <select wire:model="vr_content_company_introduction_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="vr_content_company_introduction_id">
                            <option value="">Select VR Content</option>
                            @foreach ($companyVRContents->where('content_category', 'CompanyIntroduction') as $vrContent)
                                <option value="{{ $vrContent->id }}">{{ $vrContent->content_name }}
                                    ({{ $vrContent->status }})</option>
                            @endforeach
                        </select>
                        @error('vr_content_company_introduction_id')
                            <span class="text-red-500 text-xs italic">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="vr_content_workplace_tour_id">
                            VR Content - Workplace Tour
                        </label>
                        <select wire:model="vr_content_workplace_tour_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="vr_content_workplace_tour_id">
                            <option value="">Select VR Content</option>
                            @foreach ($companyVRContents->where('content_category', 'WorkplaceTour') as $vrContent)
                                <option value="{{ $vrContent->id }}">{{ $vrContent->content_name }}
                                    ({{ $vrContent->status }})</option>
                            @endforeach
                        </select>
                        @error('vr_content_workplace_tour_id')
                            <span class="text-red-500 text-xs italic">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save Changes
                    </button>
                    <button wire:click="$set('isEditing', false)" type="button"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancel
                    </button>
                </div>
            </form>
        @endif
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('business-operator.vacancies.index') }}"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Return to Vacancies List
        </a>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-popup wire:model="showDeleteConfirmation">
        <div class="p-6">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Vacancy</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this vacancy? This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="deleteVacancy"
                        class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Delete
                    </button>
                    <button wire:click="$set('showDeleteConfirmation', false)"
                        class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </x-popup>
</div>
