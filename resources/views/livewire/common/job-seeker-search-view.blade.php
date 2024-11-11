<div class="px-4 py-12 bg-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-8 lg:flex-row">
            <!-- Sidebar -->
            <aside class="w-full lg:w-1/4">
                <div class="flex flex-col items-center text-center text-black">
                    @if ($candidate->desiredJobType)
                    <div class="bg-orange-600 text-white text-sm px-4 py-1.5 rounded-sm mb-4 uppercase">
                        @if ($candidate->desiredJobType->type){{ $candidate->desiredJobType->type === 'special_skilled' ? 'Special Skilled Worker' : $candidate->desiredJobType->type }}:@endif {{ $candidate->desiredJobType?->name ?? 'N/A' }}
                    </div>
                    @endif

                    <img src="{{ $candidate->profile_picture_link ? Storage::url($candidate->profile_picture_link) : 'https://via.placeholder.com/176' }}"
                        alt="Profile picture" class="h-auto mb-6 w-44" />
                    @if (Auth::user()->user_type !== 'Student' && Auth::user()->user_type !== 'Candidate')
                        <div class="w-44 p-6 bg-neutral-100 rounded-xl border-5 border-sky-400 lg:w-full">
                            <div class="flex justify-center space-x-4">
                                <button wire:click="toggleScout" wire:loading.attr="disabled" wire:target="toggleScout"
                                wire:loading.remove wire:target="toggleScout"
                                    class="w-full px-4 py-3 rounded-md shadow-lg text-xl  {{ $isScoutedByCurrentUser ? 'bg-green-500 text-white' : 'bg-white text-black' }} transition duration-300 hover:bg-opacity-90">
                                    {{ $isScoutedByCurrentUser ? 'Scouting' : 'Scout' }}
                                </button>
                                <div wire:loading wire:target="toggleScout" class="inline-flex items-center justify-center">
                                    <span class="font-bold text-gray-700"><i class="fa fa-spinner fa-spin"></i> <span>
                                    @if($isScoutedByCurrentUser)
                                        Canceling Scouting..
                                    @else
                                        Starting Scout...
                                    @endif
                                    </span></span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </aside>



            <!-- Main Content -->
            <article class="w-full lg:w-3/4">
                <div class="text-sm black">
                    <dl class="space-y-6">
                        @php
                            $infoItems = [
                                'Registration No.' => $candidate->id,
                                'Name' => $candidate->name,
                                'Gender' => $candidate->gender,
                                'Date of Birth' => $candidate->birth_date->format('F j, Y'),
                                'Age' => $candidate->birth_date->age . ' years old',
                                'Country of origin' => $candidate->country->country_name,
                                'Last Education' => $candidate->last_education,
                                'Work experience' => $candidate->work_history,
                                'Qualifications' => view('partials.grouped-qualifications', ['candidate' => $candidate])->render(),
                                'Japanese Language Level' => $candidate->japanese_language_qualification,
                                'Self-publicity' => $candidate->self_presentation,
                                "Person's wishes" => $candidate->personal_preference ?? 'None',
                            ];
                        @endphp

                        @foreach ($infoItems as $label => $value)
                            <div class="flex flex-col sm:flex-row">
                                <dt class="w-full pr-4 font-semibold text-right sm:w-1/3">{{ $label }}：</dt>
                                <dd class="w-full sm:w-2/3">
                                    @if ($label === 'Qualifications')
                                        {!! $value !!}
                                    @else
                                        {{ nl2br(e($value)) }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </article>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col justify-center gap-4 mt-12 sm:flex-row">
            <a href="{{ route('job-seeker.search') }}"
                class="px-6 py-3 text-sm text-center transition duration-300 bg-white border border-black rounded-md hover:bg-gray-100">
                Back to Search
            </a>
            <a href="{{ route('home') }}"
                class="px-6 py-3 text-sm text-center transition duration-300 bg-white border border-black rounded-md hover:bg-gray-100">
                Return to TOP
            </a>
        </nav>
    </div>
</div>
