<div class="container px-4 py-8 mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-center">List of VR Contents</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div></div>
        <div class="relative">
            <input
                wire:model.live="search"
                type="text"
                placeholder="Search VR contents..."
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
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">ID</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Content Name</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Category</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Company</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($vrContents as $vrContent)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->content_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->content_category_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->company ? $vrContent->company->name : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('business-operator.vr-contents.edit', $vrContent) }}" class="px-3 py-1 font-bold text-white bg-orange-500 rounded hover:bg-orange-600">
                                More
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $vrContents->links() }}
    </div>

    <div class="flex items-center justify-between gap-3 mt-6">
        <div class="w-1/3"></div>

        <a href="{{ route('home') }}" class="px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
            Return to TOP
        </a>

        <a href="{{ route('business-operator.vr-contents.create') }}" class="px-4 py-2 font-bold text-black bg-[#9CD9F1] rounded hover:bg-[#3AB2E3] hover:text-[#213238]">
            <i class="fas fa-plus text-[#267a9b] hover:text-[#344a53] font-extrabold"></i> Create New VR Content
        </a>
    </div>
</div>
