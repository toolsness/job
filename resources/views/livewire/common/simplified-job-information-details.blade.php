<div class="px-4 py-12 bg-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-5">
            <div class="lg:col-span-4">
                <div class="relative overflow-hidden rounded-lg">
                    <img src="{{ $imageUrl }}" alt="Job offer background image" class="object-cover w-full h-96">
                    <div class="absolute top-4 left-4">
                        <span class="px-4 py-2 text-sm text-black bg-orange-600 rounded-md">
                            {{ $vacancy->job_title }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-end lg:col-span-1">
                <div class="flex flex-col gap-4">
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

        <p class="mt-4 text-xl text-black"><span class="font-semibold">Job offer number：</span>{{ $vacancy->id }}</p>

        <div class="mt-12 space-y-6">
            @foreach ([
        'Shop Name' => 'companyName',
        'Industry' => 'vacancy_category_id',
        'Job Title' => 'job_title',
        'Salary' => 'monthly_salary',
        'Shop Address' => 'work_location',
        'Office Hours' => 'working_hours',
        'Transportation Expenses' => 'transportation_expenses',
        'Overtime pay' => 'overtime_pay',
        'Bonus' => 'salary_increase_and_bonuses',
        'Social insurance' => 'social_insurance',
        'Language Requirement' => 'japanese_language',
        'Other' => 'other_details',
    ] as $label => $field)
                <div class="flex flex-col gap-2 text-xl text-black sm:flex-row">
                    <dt class="w-full font-semibold text-right sm:w-2/5">{{ $label }}：</dt>
                    <dd class="w-full sm:w-3/5">
                        @if ($field === 'vacancy_category_id')
                            {{ $vacancy->vacancyCategory?->name }}
                        @elseif ($field === 'companyName')
                            {{ $companyName }}
                        @else
                            {{ $vacancy->$field }}
                        @endif
                    </dd>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Popup Modal -->
    <div x-data="{
        show: @entangle('showModal'),
        message: @entangle('modalMessage'),
    }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            Notification
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="message"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button" wire:click="closeModal"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
