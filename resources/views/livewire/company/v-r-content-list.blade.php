<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">VR Content List</h1>

    <div class="mb-4 flex justify-between">
        <div class="w-1/3">
            <input wire:model.live="search" type="text" placeholder="Search VR contents..."
                class="w-full px-4 py-2 rounded-lg border border-gray-300">
        </div>
        <div class="w-1/3">
            <select wire:model.live="statusFilter" class="w-full px-4 py-2 rounded-lg border border-gray-300">
                <option value="">All Statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content
                        Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($vrContents as $vrContent)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->content_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->content_category_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->remarks }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $vrContent->updated_at->format('d F, Y h:i A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">

                            @if (Auth::user()->user_type === 'CompanyAdmin')
                                <a href="{{ route('company.vr-contents.details', $vrContent) }}"
                                    class="ml-2 text-green-600 hover:text-green-900 border border-black px-3 py-1 hover:bg-green-200">Edit</a>
                            @else
                                <a href="{{ route('company.vr-contents.details', $vrContent) }}"
                                    class="text-indigo-600 hover:text-indigo-900 border border-black px-3 py-1 hover:bg-green-200">View</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $vrContents->links() }}
    </div>

    <div class="text-center my-6">
        <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back
            to Home</a>
    </div>
</div>
