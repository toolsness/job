<div class="bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-semibold text-center mb-8">Confirm the job description and the job seekers you are scouting for</h1>

        <div class="flex flex-col md:flex-row justify-center items-center gap-8">
            <!-- Job Card -->
            <div class="w-full md:w-1/2 bg-white rounded-lg border border-gray-200 overflow-hidden shadow-lg">
                <div class="p-6">
                    <div class="pb-4">
                        <div class="inline-block bg-orange-600 text-white text-xs font-semibold px-4 py-2 rounded-md shadow-sm">
                            {{ $job->company->industryType->name ?? '' }}
                        </div>
                    </div>

                    <img src="{{ $job->image ? Storage::url($job->image) : 'https://picsum.photos/id/1/600/250' }}" alt="{{ $job->title }}" class="w-full h-64 object-cover mb-4 rounded-md shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">{{ $job->job_title }}</h2>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Company:</span> {{ $job->company->name }}</p>
                        <p><span class="font-semibold">Business Type:</span> {{ $job->vacancyCategory->name }}</p>
                        <p><span class="font-semibold">Salary:</span> {{ $job->monthly_salary }} ¥</p>
                        <p><span class="font-semibold">Location:</span> {{ $job->work_location }}</p>
                        <p><span class="font-semibold">Japanese Level:</span> {{ $job->japanese_language }}</p>
                    </div>
                </div>
            </div>

            <!-- Arrow Icon -->
            <div class="text-4xl text-gray-400 hidden md:block">
                <i class="fas fa-arrow-right"></i>
            </div>

            <!-- Candidate Card -->
            <div class="w-full md:w-1/2 bg-white rounded-lg border border-gray-200 overflow-hidden shadow-lg">
                <div class="p-6">
                    <div class="pb-4">
                        <div class="inline-block bg-orange-600 text-white text-xs font-semibold px-4 py-2 rounded-md shadow-sm">
                            <span class="whitespace-nowrap overflow-hidden text-ellipsis max-w-[200px] block uppercase">
                                @if ($candidate->desiredJobType)
                                    @if ($candidate->desiredJobType->type)
                                        {{ $candidate->desiredJobType->type === 'special_skilled' ? 'Special Skillled Worker': $candidate->desiredJobType->type }}: {{ $candidate->desiredJobType->name ?? 'N/A' }}
                                    @else
                                        {{ $candidate->desiredJobType->name ?? 'N/A' }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    </div>
                    <img src="{{ $candidate->profile_picture_link ? Storage::url($candidate->profile_picture_link) : 'https://picsum.photos/id/1/600/250' }}" alt="{{ $candidate->name }}" class="w-32 h-32 object-cover mb-4 rounded-full shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">{{ $candidate->name }}</h2>
                    <div class="space-y-2">
                        <p><span class="font-semibold">Age:</span> {{ $candidate->birth_date?->age ?? 'N/A' }} years old</p>
                        <p><span class="font-semibold">Gender:</span> {{ $candidate?->gender ?? 'N/A' }}</p>
                        <p><span class="font-semibold">Country:</span> {{ $candidate->country?->country_name ?? 'N/A' }}</p>
                        <p><span class="font-semibold">Last Education:</span> {{ $candidate?->last_education ?? 'N/A' }}</p>
                        <p><span class="font-semibold">Work Experience:</span> {{ $candidate?->work_history ?? 'N/A' }}</p>
                        <p><span class="font-semibold">Japanese Language Level:</span> {{ $candidate?->japanese_language_qualification ?? 'N/A' }}</p>
                        <p><span class="font-semibold">Qualifications:</span> {{ $candidate?->qualifications->pluck('qualification_name')->implode(', ') ?? 'N/A' }}</p>
                        <p><span class="font-semibold">About Yourself:</span> {{ $candidate?->self_presentation ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <p class="text-lg mb-6">Would you like to scout for candidates here for this position?</p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <button wire:loading.attr="disabled" wire:loading.remove wire:target="confirmScouting" wire:click="confirmScouting"
                    class="px-8 py-3 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-300 shadow-sm">
                    Confirm Scouting
                </button>
                <div wire:loading wire:target="confirmScouting" class="inline-flex items-center justify-center">
                    <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>Starting Scouting...</span></span>
                </div>
                <button wire:loading.attr="disabled" wire:loading.remove wire:target="cancelScouting" wire:click="cancelScouting"
                    class="px-8 py-3 text-sm text-black bg-white rounded-md border border-gray-300 hover:bg-gray-100 transition duration-300 shadow-sm">
                    Cancel
                </button>
                <div wire:loading wire:target="cancelScouting" class="inline-flex items-center justify-center">
                    <span class="font-bold text-gray-700"><i class="fa fa-spinner fa-spin"></i> <span>Canceling...</span></span>
                </div>
            </div>
        </div>
    </div>
</div>
