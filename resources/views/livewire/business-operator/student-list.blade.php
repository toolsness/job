<div class="container px-4 py-8 mx-auto">
    <h1 class="mb-6 text-2xl font-bold text-center">Student List</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div></div>
        <div class="relative">
            <input
                wire:model.live="search"
                type="text"
                placeholder="Search students..."
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
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Name (Kanji)</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Name (Katakana)</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-black uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($students as $student)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($student->user->image)
                                    <img src="{{ Storage::url($student->user->image) }}" alt="{{ $student->user->name }}" class="w-10 h-10 mr-3 rounded-full">
                                @else
                                    <div class="w-10 h-10 mr-3 bg-gray-300 rounded-full"></div>
                                @endif
                                {{ $student->user->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->name_kanji }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->name_katakana }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($student->candidate)
                                <button wire:click="openModal({{ $student->id }})" class="px-3 py-1 font-bold text-white bg-orange-500 rounded hover:bg-orange-600">
                                    More
                                </button>
                            @else
                                <a href="{{ route('business-operator.students.edit', $student) }}" class="px-3 py-1 font-bold text-white bg-orange-500 rounded hover:bg-orange-600">
                                    Edit
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $students->links() }}
    </div>

    <div class="flex items-center justify-between gap-3 mt-6">
        <div class="w-1/3"></div>

        <a href="{{ route('home') }}" class="px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
            Return to TOP
        </a>

        <a href="{{ route('business-operator.students.create') }}" class="px-4 py-2 font-bold text-black bg-[#9CD9F1] rounded hover:bg-[#3AB2E3] hover:text-[#213238]">
            <i class="fas fa-plus text-[#267a9b] hover:text-[#344a53] font-extrabold"></i> New Student Registration
        </a>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                    Edit Options
                                </h3>
                                <div class="mt-2">
                                    <a href="{{ route('business-operator.students.edit', $selectedStudentId) }}" class="inline-block w-full px-4 py-2 mb-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                                        Edit Student Info
                                    </a>
                                    @if ($candidateId)
                                        <h4 class='py-4 text-sm font-medium leading-6 text-gray-600'>Might have CV. If you want to edit it, please click below button!</h4>
                                        <a href="{{ route('business-operator.job-seekers.edit', $candidateId) }}" class="inline-block w-full px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700">
                                            Edit CV Info
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="closeModal" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
