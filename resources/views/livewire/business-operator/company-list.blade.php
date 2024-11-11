<div class="container px-4 py-8 mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-center">List of registered companies</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div></div>
        <div class="relative">
            <input
                wire:model.live="search"
                type="text"
                placeholder="Specific Skills, Food Service"
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
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Company ID</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Company Image</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Company Name</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Name (Kanji)</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Name (Katakana)</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Email Address</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Company Phone Number</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Type of Industry</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Address</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Website</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($companies as $company)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $company->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="{{ $company->image ? Storage::url($company->image) : asset('images/placeholder.png') }}"
                             alt="{{ $company->name }}"
                             class="w-10 h-10 rounded-full object-cover">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $company->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $company->name_kanji }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $company->name_katakana }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $company->contact_email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $company->contact_phone }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $company->industryType->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $company->address }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ $company->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $company->website }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('business-operator.companies.edit', $company) }}" class="px-3 py-1 font-bold text-white bg-orange-500 rounded hover:bg-orange-600">
                            More
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $companies->links() }}
    </div>

    <div class="flex items-center justify-between gap-3 mt-6">
        <div class="w-1/3"></div>

        <a href="{{ route('home') }}" class="px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
            Return to TOP
        </a>

        <a href="{{ route('business-operator.companies.create') }}" class="px-4 py-2 font-bold text-black bg-[#9CD9F1] rounded hover:bg-[#3AB2E3] hover:text-[#213238]">
            <i class="fas fa-plus text-[#267a9b] hover:text-[#344a53] font-extrabold"></i> New Company Registration
        </a>


    </div>
</div>
