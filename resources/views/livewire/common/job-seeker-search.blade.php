<div>
    <main class="flex items-center justify-center px-2 text-black bg-white sm:px-4 md:px-16 sm:py-2 md:py-2">
        <section class="flex flex-col w-full max-w-[889px]">
            <h1 class="mb-4 text-xl text-center sm:text-2xl sm:mb-8">List of registered candidates</h1>

            <form wire:submit.prevent="$refresh" class="flex flex-col sm:flex-row gap-2 sm:gap-4 justify-between px-2 sm:px-4 py-2 sm:py-3 mb-4 sm:mb-8 w-full max-w-[889px] mx-auto text-sm bg-white rounded-md border border-black">
                <div class="flex flex-col w-full gap-2 sm:flex-row sm:gap-4">
                    <select wire:model.live="desiredIndustry" class="w-full p-2 border border-gray-300 rounded sm:w-1/4">
                        <option value="">Desired Industry</option>
                        @foreach ($industries as $industry)
                            <option value="{{ $industry->name }}">{{ $industry->name }} ({{ $industry->candidate_count }} {{ $industry->candidate_count == 1 ? 'Candidate' : 'Candidates' }})</option>
                        @endforeach
                    </select>

                    <select wire:model.live="country" class="w-full p-2 border border-gray-300 rounded sm:w-1/4">
                        <option value="">Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->country_name }}">{{ $country->country_name }} ({{ $country->candidate_count }} {{ $country->candidate_count == 1 ? 'Candidate' : 'Candidates' }})</option>
                        @endforeach
                    </select>

                    <div x-data='ageRange' wire:ignore class="w-full pl-6 sm:w-1/4">
                        <label for="ageRange" class="block mb-2 text-sm font-medium text-gray-700"
                            x-text='ageMin + " - " + ageMax + " Years Old"'>Age Range: <span
                                id="age-range">{{ $ageMin }} - {{ $ageMax }}</span></label>
                        <div id="age-slider" x-ref='slider' class="mt-2" style="height: 10px; width: 80%;"></div>
                    </div>

                    <div class="relative w-full sm:w-1/4">
                        <input wire:model.live="search" id="searchInput" type="text" placeholder="Search freely"
                            class="w-full p-2 pl-3 pr-10 border border-gray-300 rounded"
                            aria-label="Search Candidates" />
                        <i
                            class="absolute text-gray-400 transform -translate-y-1/2 fa-solid fa-magnifying-glass right-3 top-1/2"></i>
                    </div>
                </div>
            </form>

            <div class="-mx-2 overflow-x-auto sm:mx-0">
                <table class="w-full text-xs font-semibold text-center bg-opacity-50 sm:text-sm">
                    <thead class="bg-sky-400">
                        <tr class="border-b border-black">
                            <th class="p-2 sm:p-3">Registration No.</th>
                            <th class="p-2 sm:p-3">Name</th>
                            <th class="p-2 sm:p-3">Country</th>
                            <th class="p-2 sm:p-3">Age</th>
                            <th class="p-2 sm:p-3">Gender</th>
                            <th class="p-2 sm:p-3">Desired Industry</th>
                            <th class="p-2 sm:p-3">Japanese Level</th>
                            {{-- <th class="p-2 sm:p-3">Qualifications</th> --}}
                            <th class="p-2 sm:p-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidates as $candidate)
                            <tr class="border-b border-gray-200">
                                <td class="p-2 sm:p-3">{{ $candidate->id }}</td>
                                <td class="p-2 sm:p-3">{{ $candidate->name }}</td>
                                <td class="p-2 sm:p-3">{{ $candidate->country->country_name }}</td>
                                <td class="p-2 sm:p-3">
                                    {{ \Carbon\Carbon::parse($candidate->birth_date)->age }}
                                </td>
                                <td class="p-2 sm:p-3">{{ $candidate->gender }}</td>
                                <td class="p-2 sm:p-3">{{ $candidate?->desiredJobType->name ?? 'N/A' }}</td>
                                <td class="p-2 sm:p-3">{{ $candidate?->japanese_language_qualification ?? 'N/A' }}</td>
                                {{-- <td class="p-2 sm:p-3">
                                    @foreach($candidate->qualifications as $qualification)
                                        <span class="mx-2 bg-slate-600 text-white">{{ $qualification->qualification_name }}</span>, <p> </p>
                                    @endforeach
                                </td> --}}
                                <td class="p-2 sm:p-3">
                                    <a href="{{ route('job-seeker.view', ['id' => $candidate->id, 'search' => $search, 'page' => $this->page]) }}"
                                        class="px-2 py-1 text-xs text-white bg-orange-600 rounded-md shadow-md sm:px-3 sm:text-sm">
                                        More
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $candidates->links() }}
            </div>

            <div class="sm:flex-row gap-4 justify-between mt-6 sm:mt-12 w-full max-w-[176px] mx-auto px-2 sm:px-0">
                <a href="{{ url('/') }}"
                    class="flex items-center justify-center w-full px-4 py-2 text-xs text-center bg-white border border-black rounded-md sm:px-6 sm:py-3 sm:text-sm sm:w-auto">
                    Return to TOP
                </a>
            </div>
        </section>
    </main>

    @assets
    <style>
        #age-slider {
            height: 10px;
        }

        #age-slider .noUi-connect {
            background: #FFA500; /* Orange background for the slider */
        }

        #age-slider .noUi-handle {
            height: 18px;
            width: 18px;
            top: -5px;
            right: -9px;
            border-radius: 9px; /* Make the handle round */
            background: #ffffff; /* Orange color for the handle */
            border: .5px solid #afafaf;
            box-shadow: none;
        }

        #age-slider .noUi-handle::before,
        #age-slider .noUi-handle::after {
            display: none; /* Remove the lines inside the handle */
        }

        #age-slider .noUi-touch-area {
            border: 1px solid transparent;
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            width: auto;
            height: auto;
        }

        #age-slider .noUi-handle:hover .noUi-touch-area {
            /* border: 1px dashed #ffffff; */
        }

        /* Customize the slider bar */
        #age-slider .noUi-base {
            background: #b8b8b8; /* Light orange background for the base */
            border-radius: 5px; /* Slightly round the edges of the base */
        }
    </style>
@endassets

@script
    <script>
        Alpine.data('ageRange', () => ({
            ageMin: 18,
            ageMax: 50,
            init() {
                noUiSlider.create(this.$refs.slider, {
                    start: [this.ageMin, this.ageMax],
                    connect: true,
                    range: {
                        'min': 18,
                        'max': 50
                    },
                    step: 1
                });

                this.$refs.slider.noUiSlider.on('update', (values, handle) => {
                    if (handle) {
                        this.ageMax = Math.round(values[handle]);
                        this.$wire.set('ageMax', this.ageMax, true);
                    } else {
                        this.ageMin = Math.round(values[handle]);
                        this.$wire.set('ageMin', this.ageMin, true);
                    }
                });
            }
        }))
    </script>
@endscript
</div>
