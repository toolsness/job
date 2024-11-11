<div class="bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <div class="lg:col-span-4">
                <div class="relative rounded-lg overflow-hidden">
                    <img src="{{ $vacancy->image ? asset('storage/' . $vacancy->image) : asset('placeholder2.png') }}"
                        alt="Job offer background image" class="w-full h-96 object-cover">
                    <div class="absolute top-4 left-4">
                        <span class="px-4 py-2 bg-orange-600 text-black text-sm rounded-md">
                            {{ $vacancy->job_title }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1 flex flex-col justify-end">
                <div class="flex flex-col gap-4">
                    <a href="{{ route('job.vr-content', ['vacancyId' => $vacancy->id, 'contentType' => 'Company Introduction']) }}"
                        class="w-full py-2 px-4 bg-white text-black text-sm border border-black rounded-md hover:bg-gray-100 transition">
                        VR Company Information
                    </a>
                    <a href="{{ route('job.vr-content', ['vacancyId' => $vacancy->id, 'contentType' => 'Workplace Tour']) }}"
                        class="w-full py-2 px-4 bg-white text-black text-sm border border-black rounded-md hover:bg-gray-100 transition">
                        VR Workplace Tour
                    </a>
                    <a href="{{ route('student.interview-application', $vacancy) }}"
                        class="w-full py-2 px-4 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">
                        Apply for Interview
                    </a>
                </div>
            </div>
        </div>

        <p class="mt-4 text-xl text-black"><span class="font-semibold">Job offer number：</span>{{ $vacancy->id }}</p>

        <div class="mt-12 space-y-6">
            @foreach ([
        'Shop Name' => $vacancy->companyRepresentative->company->name,
        'Industry' => $vacancy->vacancyCategory->name,
        'Job Title' => $vacancy->job_title,
        'Salary' => $vacancy->monthly_salary,
        'Shop Address' => $vacancy->work_location,
        'Office Hours' => $vacancy->working_hours,
        'Transportation Expenses' => $vacancy->transportation_expenses,
        'Overtime pay' => $vacancy->overtime_pay,
        'Bonus' => $vacancy->salary_increase_and_bonuses,
        'Social insurance' => $vacancy->social_insurance,
        'Language Requirement' => $vacancy->japanese_language,
        'Other' => $vacancy->other_details,
    ] as $label => $value)
                <div class="flex flex-col sm:flex-row gap-2 text-xl text-black">
                    <dt class="w-full sm:w-1/3 text-right font-semibold">{{ $label }}：</dt>
                    <dd class="w-full sm:w-2/3">
                        {{ $value }}
                    </dd>
                </div>
            @endforeach
        </div>
    </div>

    <nav class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
        <a href="{{ route('student.job-search') }}"
            class="px-6 py-3 bg-white text-black text-md text-center border border-black rounded-md hover:bg-gray-100 transition">
            Return to Job List
        </a>
        <a href="{{ route('home') }}"
            class="px-6 py-3 bg-white text-black text-md text-center border border-black rounded-md hover:bg-gray-100 transition"
            onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">
            Return to TOP
        </a>
    </nav>
</div>
