<div class="container px-4 py-8 mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-center">Job Seeker List</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div></div>
        <div class="relative">
            <input
                wire:model.live="search"
                type="text"
                placeholder="Search job seekers..."
                class="w-full px-4 py-2 pr-10 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <div></div>
    </div>

    <div class="overflow-hidden overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#3AB2E3]">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Student ID</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Name</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Email</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Gender</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Nationality</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Last Education</th>
                    {{-- <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Qualifications</th> --}}
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Desired Job Sector</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($jobSeekers as $jobSeeker)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $jobSeeker->student->user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($jobSeeker->student->user->image)
                                    <img src="{{ Storage::url($jobSeeker->student->user->image) }}" alt="{{ $jobSeeker->name }}" class="w-10 h-10 mr-3 rounded-full">
                                @else
                                    <div class="w-10 h-10 mr-3 bg-gray-300 rounded-full"></div>
                                @endif
                                {{ $jobSeeker->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $jobSeeker->student->user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $jobSeeker->gender }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $jobSeeker->country->country_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $jobSeeker->last_education }}</td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap">
                            {{ $jobSeeker->qualifications->pluck('qualification_name')->implode(', ') }}
                        </td> --}}
                        <td class="px-6 py-4 whitespace-nowrap">{{ $jobSeeker->desiredJobType->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('business-operator.job-seekers.edit', $jobSeeker) }}" class="px-3 py-1 font-bold text-white bg-orange-500 rounded hover:bg-orange-600">
                                More
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $jobSeekers->links() }}
    </div>

    <div class="flex items-center justify-between gap-3 mt-6">
        <div class="w-1/3"></div>

        <a href="{{ route('home') }}" class="px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
            Return to TOP
        </a>

        <a href="{{ route('business-operator.job-seekers.create') }}" class="px-4 py-2 font-bold text-black bg-[#9CD9F1] rounded hover:bg-[#3AB2E3] hover:text-[#213238]">
            <i class="fas fa-plus text-[#267a9b] hover:text-[#344a53] font-extrabold"></i> New Job Seeker Registration
        </a>
    </div>
</div>
