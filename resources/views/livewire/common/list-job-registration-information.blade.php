<div class="bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <header class="items-center mb-8">
            <h1 class="text-xl font-semibold text-center mb-4 sm:mb-8">List of Jobs</h1>
            <div class="relative w-full max-w-[550px] mx-auto">
                <input wire:model.live="search" id="searchInput" type="text" placeholder="Search by job category"
                    class="w-full p-2 pl-3 pr-10 border border-gray-300 rounded" aria-label="Search Job" />
                <i class="fa-solid fa-magnifying-glass absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </header>
        <p class="text-xl font-semibold text-black my-6">Job Listings: <span class="text-red-600">{{ $vacancies->total() }}</span></p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @if (in_array($userType, ['CompanyAdmin', 'CompanyRepresentative']))
                <a href="{{ route('job-list.create') }}" title="Create a new Job"
                    class="bg-white rounded-lg border border-black overflow-hidden shadow-sm hover:shadow-md transition duration-300 ease-in-out flex items-center justify-center h-full min-h-[12rem]">
                    <div class="flex items-center justify-center w-full h-full">
                        <i class="fa-solid fa-plus text-9xl text-gray-400"></i>
                    </div>
                </a>
            @endif
            @foreach ($jobListings as $jobListing)
                <a href="{{ route('job-details', ['id' => $jobListing['id']]) }}"
                    class="bg-white rounded-lg border border-black overflow-hidden shadow-sm hover:shadow-md transition duration-300 ease-in-out">
                    <div class="bg-white rounded-lg overflow-hidden">
                        <div class="relative">
                            <img src="{{ $jobListing['image'] ? Storage::url($jobListing['image']) : asset('placeholder2.png') }}"
                                alt="Job Image" class="w-full h-48 object-cover">
                            <div class="absolute top-0 left-0 bg-orange-500 text-white px-2 py-1 text-sm">
                                {{ $jobListing['businessType'] }} @if (in_array($userType, ['Candidate', 'Student'])), {{ $jobListing['category'] }}@endif
                            </div>
                            <div class="absolute bottom-0 right-0 bg-white text-black px-2 py-1 text-xs">
                                Job offer number {{ $jobListing['offerNumber'] }}
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="space-y-2">
                                @if (in_array($userType, ['Candidate', 'Student']))
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    <span class="text-sm">Company: {{ $jobListing['companyName'] }}</span>
                                </div>
                                @else
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    <span class="text-sm">Industry: {{ $jobListing['category'] }}</span>
                                </div>
                                @endif
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <span class="text-sm">Salary: {{ $jobListing['salaryInfo'] }} ¥</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm">Address: {{ $jobListing['shopAddress'] }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="9.78394" cy="9.91987" r="9.44916" stroke="currentColor"
                                            stroke-width="0.5" />
                                        <path
                                            d="M5.25411 4.964H13.7691V15.845H12.7421V5.952H6.25511V15.897H5.25411V4.964ZM5.91711 9.475H13.1711V10.437H5.91711V9.475ZM5.90411 14.077H13.1971V15.052H5.90411V14.077Z"
                                            fill="black" />
                                    </svg>
                                    <span class="text-sm">Language Requirement:
                                        {{ $jobListing['japaneseLevel'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach

        </div>
        <div class="mt-8 text-center">
            {{ $vacancies->links() }}
        </div>

        <div class="mt-12 text-center">
            {{-- <button
                class="px-8 py-3 text-sm text-black bg-white rounded-md border border-black hover:bg-gray-100 transition duration-300"
                onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">
                Return to TOP
            </button> --}}
            <a href="{{ route('home') }}"
                class="px-8 py-3 text-sm text-black bg-white rounded-md border border-black hover:bg-gray-100 transition duration-300">
                Return to TOP
        </a>
        </div>
    </div>
</div>
