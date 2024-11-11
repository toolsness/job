<div class="container px-4 py-8 mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-center">Business Operator List</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">

        <div class="relative">
            <input
                wire:model.live="search"
                type="text"
                placeholder="Search business operators..."
                class="w-full px-4 py-4 pr-10 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <div></div>
        <div class="relative">
            <label for="tagFilter" class="block text-gray-700 text-sm font-bold mb-2">Filter by Responsibility:</label>
            <select wire:model.live="tagFilter" id="tagFilter"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">All Tags</option>
                <option value="general">General</option>
                <option value="application">Application</option>
                <option value="interview">Interview</option>
                <option value="technical">Technical</option>
            </select>
        </div>
    </div>


    <div class="overflow-hidden overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#3AB2E3]">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">User ID</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Name</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Email</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Name (Kanji)</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Name (Katakana)</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Responsibility</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($businessOperators as $businessOperator)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $businessOperator->user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($businessOperator->user->image)
                                    <img src="{{ Storage::url($businessOperator->user->image) }}" alt="{{ $businessOperator->user->name }}" class="w-10 h-10 mr-3 rounded-full">
                                @else
                                    <div class="w-10 h-10 mr-3 bg-gray-300 rounded-full"></div>
                                @endif
                                {{ $businessOperator->user->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $businessOperator->user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $businessOperator->name_kanji }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $businessOperator->name_katakana }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">@if ( $businessOperator->tag ) <span class="@if ($businessOperator->tag == 'general') bg-purple-200 text-purple-800
                            @elseif($businessOperator->tag == 'application') bg-green-200 text-green-800
                            @elseif($businessOperator->tag == 'interview') text-yellow-600 bg-black
                            @elseif($businessOperator->tag == 'technical') bg-red-200 text-red-800
                            @else text-yellow-600 bg-black @endif rounded-xl border px-2 py-2 capitalize font-mono"> {{ $businessOperator->tag }}</span> @else N/A @endif</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('business-operator.business-operators.edit', $businessOperator) }}" class="px-3 py-1 font-bold text-white bg-orange-500 rounded hover:bg-orange-600">
                                Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $businessOperators->links() }}
    </div>

    <div class="flex items-center justify-between gap-3 mt-6">
        <div class="w-1/3"></div>

        <a href="{{ route('home') }}" class="px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
            Return to TOP
        </a>

        <a href="{{ route('business-operator.business-operators.create') }}" class="px-4 py-2 font-bold text-black bg-[#9CD9F1] rounded hover:bg-[#3AB2E3] hover:text-[#213238]">
            <i class="fas fa-plus text-[#267a9b] hover:text-[#344a53] font-extrabold"></i> New Business Operator Registration
        </a>
    </div>
</div>
