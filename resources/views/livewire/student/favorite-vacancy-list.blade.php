<div class="container px-4 py-8 mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-center">Favorite Vacancies</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div></div>
        <div class="relative">
            <input
                wire:model.live="search"
                type="text"
                placeholder="Search vacancies..."
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
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Job Title</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Company</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Industry</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Salary</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($favoriteVacancies as $vacancy)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $vacancy->job_title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $vacancy->company->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $vacancy->vacancyCategory->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $vacancy->monthly_salary }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('job-details', ['id' => $vacancy->id]) }}" class="px-3 py-1 mr-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-600">
                            Details
                        </a>
                        <button wire:click="removeFromFavorites({{ $vacancy->id }})" class="px-3 py-1 font-bold text-white bg-red-500 rounded hover:bg-red-600">
                            Remove
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $favoriteVacancies->links() }}
    </div>

    <div class="flex items-center justify-center mt-6">
        <a href="{{ route('home') }}" class="px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
            Return to TOP
        </a>
    </div>
</div>
