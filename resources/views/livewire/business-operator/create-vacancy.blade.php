<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Create New Vacancy</h2>

    <form wire:submit.prevent="save" class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="company_id">
                Company
            </label>
            <select wire:model.live="company_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="company_id">
                <option value="">Select a company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
            @error('company_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        @if($selectedCompany)
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="user_type">
                    User Type
                </label>
                <select wire:model.live="user_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user_type">
                    <option value="">Select user type</option>
                    <option value="CompanyAdmin">Company Admin ({{ $selectedCompany->companyAdmins->count() }})</option>
                    <option value="CompanyRepresentative">Company Representative ({{ $selectedCompany->companyRepresentatives->count() }})</option>
                </select>
                @error('user_type') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
            </div>

            @if($user_type)
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="selected_user_id">
                        Select User
                    </label>
                    <select wire:model.live="selected_user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="selected_user_id">
                        <option value="">Select a user</option>
                        @foreach($companyUsers as $user)
                            <option value="{{ $user->user_id }}">{{ $user->user->name }} ({{ $user_type }})</option>
                        @endforeach
                    </select>
                    @error('selected_user_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
            @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="publish_category">
                Publish Category
            </label>
            <select wire:model="publish_category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="publish_category">
                <option value="">Select a category</option>
                <option value="NotPublished">Not Published</option>
                <option value="Published">Published</option>
                <option value="PublicationStopped">Publication Stopped</option>
            </select>
            @error('publish_category') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                Image
            </label>
            <input wire:model="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="image" type="file">
            @error('image') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="vacancy_category_id">
                Vacancy Category
            </label>
            <select wire:model="vacancy_category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="vacancy_category_id">
                <option value="">Select a category</option>
                @foreach($vacancyCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('vacancy_category_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="job_title">
                Job Title
            </label>
            <input wire:model="job_title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="job_title" type="text">
            @error('job_title') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="monthly_salary">
                Monthly Salary
            </label>
            <input wire:model="monthly_salary" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="monthly_salary" type="text">
            @error('monthly_salary') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="work_location">
                Work Location
            </label>
            <input wire:model="work_location" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="work_location" type="text">
            @error('work_location') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="working_hours">
                Working Hours
            </label>
            <input wire:model="working_hours" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="working_hours" type="text">
            @error('working_hours') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="transportation_expenses">
                Transportation Expenses
            </label>
            <input wire:model="transportation_expenses" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="transportation_expenses" type="text">
            @error('transportation_expenses') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="overtime_pay">
                Overtime Pay
            </label>
            <input wire:model="overtime_pay" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="overtime_pay" type="text">
            @error('overtime_pay') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="salary_increase_and_bonuses">
                Salary Increase and Bonuses
            </label>
            <input wire:model="salary_increase_and_bonuses" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="salary_increase_and_bonuses" type="text">
            @error('salary_increase_and_bonuses') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="social_insurance">
                Social Insurance
            </label>
            <input wire:model="social_insurance" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="social_insurance" type="text">
            @error('social_insurance') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="japanese_language">
                Japanese Language Requirement
            </label>
            <input wire:model="japanese_language" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="japanese_language" type="text">
            @error('japanese_language') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="other_details">
                Other Details
            </label>
            <textarea wire:model="other_details" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="other_details"></textarea>
            @error('other_details') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="vr_content_company_introduction_id">
                VR Content - Company Introduction
            </label>
            <select wire:model="vr_content_company_introduction_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="vr_content_company_introduction_id">
                <option value="">Select VR Content</option>
                @foreach($companyVRContents->where('content_category', 'CompanyIntroduction') as $vrContent)
                    <option value="{{ $vrContent->id }}">{{ $vrContent->content_name }} ({{ $vrContent->status }})</option>
                @endforeach
            </select>
            @error('vr_content_company_introduction_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="vr_content_workplace_tour_id">
                VR Content - Workplace Tour
            </label>
            <select wire:model="vr_content_workplace_tour_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="vr_content_workplace_tour_id">
                <option value="">Select VR Content</option>
                @foreach($companyVRContents->where('content_category', 'WorkplaceTour') as $vrContent)
                    <option value="{{ $vrContent->id }}">{{ $vrContent->content_name }} ({{ $vrContent->status }})</option>
                @endforeach
            </select>
            @error('vr_content_workplace_tour_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>
    @endif

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Create Vacancy
        </button>
        <a href="{{ route('business-operator.vacancies.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Cancel
        </a>
    </div>
</form>
</div>
