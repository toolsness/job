<div class="px-4 py-12 bg-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-8 lg:flex-row">
            <!-- Sidebar -->
            <aside class="w-full lg:w-1/6">
                <div class="flex flex-col items-center text-center text-black">
                    @if ($candidate->desiredJobType)
                    <div class="bg-orange-600 text-white text-sm px-4 py-1.5 rounded-sm mb-4 uppercase">
                        {{-- @if ($candidate->desiredJobType->type){{ ucfirst($candidate->desiredJobType->type) }}:@endif --}}
                        Special Skilled Worker: {{ ucfirst($candidate->desiredJobType->name) }}
                    </div>
                    @endif
                    <img src="{{ $candidate->profile_picture_link ? Storage::url($candidate->profile_picture_link) : 'https://via.placeholder.com/176' }}"
                        alt="Profile picture" class="h-auto mb-6 w-44" />
                </div>
            </aside>

            <!-- Main Content -->
            <article class="w-full lg:w-5/6">
                <div class="text-sm black">
                    <dl class="space-y-6">
                        @php
                            $infoItems = [
                                'Registration No.' => $candidate->id,
                                'Name' => $candidate->name,
                                'Gender' => $candidate->gender,
                                'Date of Birth' => $candidate->birth_date->format('F j, Y'),
                                'Age' => $candidate->birth_date->age . ' years old',
                                'Country' => $candidate->country->country_name,
                                'Last Education' => $candidate->last_education,
                                'Work Experience' => $candidate->work_history,
                                'Japanese Language Level' => $candidate->japanese_language_qualification,
                                'Qualifications' => view('partials.grouped-qualifications', ['candidate' => $candidate])->render(),
                                'Self-publicity' => $candidate->self_presentation,
                                'Person\'s wishes' => $candidate->personal_preference ?? 'None',
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
    </div>
</div>
