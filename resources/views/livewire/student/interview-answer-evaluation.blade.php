<div class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Interview Practice Evaluations</h1>
            <p class="text-gray-600">Review and analyze your past interview practice evaluations</p>
        </div>

        {{-- <!-- Practice Type Switcher -->
        <div class="mb-8 flex justify-center">
            <button wire:click="switchPracticeType('text')"
                class="px-4 py-2 rounded-l-lg {{ $practiceType === 'text' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">Writing
                Practice</button>
            <button wire:click="switchPracticeType('voice')"
                class="px-4 py-2 rounded-r-lg {{ $practiceType === 'voice' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">Voice
                Practice</button>
        </div> --}}

        <!-- Achievement Tags -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Your Achievements</h2>
            <div class="flex flex-wrap gap-2">
                {{-- Commenting out text practice achievements
        @if ($practiceType === 'text')
            @foreach ($passedModules as $module => $score)
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                    <i class="fa-solid fa-award"></i>
                    {{ ucfirst(str_replace('_', ' ', $module)) }}: Passed ({{ $score }}/100)
                </span>
            @endforeach
        @else
        --}}
                @foreach ($voicePassedModules as $module => $score)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                        <i class="fa-solid fa-microphone"></i>
                        {{ ucfirst(str_replace('_', ' ', $module)) }}: Passed ({{ $score }}/100)
                    </span>
                @endforeach
                {{-- @endif --}}
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-8 bg-gray-50 p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Filters</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input wire:model.live="search" type="text" id="search"
                        placeholder="Search in questions or answers"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input wire:model.live="dateFrom" type="date" id="dateFrom"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input wire:model.live="dateTo" type="date" id="dateTo"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="scoreFilter" class="block text-sm font-medium text-gray-700 mb-1">Score Range</label>
                    <select wire:model.live="scoreFilter" id="scoreFilter"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">All Scores</option>
                        <option value="0-50">0-50</option>
                        <option value="51-70">51-70</option>
                        <option value="71-90">71-90</option>
                        <option value="91-100">91-100</option>
                    </select>
                </div>
                <div>
                    <label for="questionTypeFilter" class="block text-sm font-medium text-gray-700 mb-1">Question
                        Type</label>
                    <select wire:model.live="questionTypeFilter" id="questionTypeFilter"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">All Types</option>
                        @foreach ($questionTypes as $type)
                            <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFilters"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Practice List -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b text-left">Date</th>
                        <th class="py-2 px-4 border-b text-left">Practice Item</th>
                        <th class="py-2 px-4 border-b text-left">Overall Score</th>
                        <th class="py-2 px-4 border-b text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($practices as $practice)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">{{ $practice->created_at->format('F j, Y H:i') }}</td>
                            <td class="py-2 px-4 border-b">
                                {{ ucfirst(str_replace('_', ' ', $practice->interviewWritingPractice->question_type ?? 'N/A')) }}
                            </td>
                            <td class="py-2 px-4 border-b">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $practice->overall_score >= 80
                                ? 'bg-green-100 text-green-800'
                                : ($practice->overall_score >= 60
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : 'bg-red-100 text-red-800') }}">
                                    {{ $practice->overall_score ?? 'N/A' }}/100
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <button wire:click="showPractice({{ $practice->id }})"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded text-sm">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 px-4 border-b text-center text-gray-500">
                                No voice practice evaluations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $practices->links() }}
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('interview-practice.voice') }}"
                class="border-black text-black border hover:bg-black hover:text-white py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Back to Interview Test
            </a>
            <a href="{{ route('home') }}" class="border-black text-black border hover:bg-black hover:text-white py-2 px-4 rounded">
               <i class="fas fa-home mr-2"></i> Return to TOP page
            </a>
        </div>
    </div>

    {{--
    <!-- Practice Modal -->
    <x-popup wire:model="showPracticeModal">
        <div class="p-6 max-h-[80vh] overflow-y-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Practice Details</h3>
            @if ($selectedPractice)
                <div class="mt-2">
                    <p class="text-sm text-gray-500 mb-2">
                        <strong>Practice Item:</strong>
                        {{ ucfirst(str_replace('_', ' ', $selectedPractice->options->first()->option_type ?? 'N/A')) }}
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        <strong>Question:</strong>
                        <span
                            class="whitespace-pre-wrap">{{ $selectedPractice->options->first()->question ?? 'N/A' }}</span>
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        <strong>Your Answer:</strong> <span
                            class="whitespace-pre-wrap">{{ $selectedPractice->options->first()->user_text ?? 'N/A' }}</span>
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        <strong>Improved Answer:</strong> <span
                            class="whitespace-pre-wrap">{{ $selectedPractice->options->first()->improved_answer ?? 'N/A' }}</span>
                    </p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-lg mb-2">Feedback</h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p><strong>Overall Score:</strong> <span
                                        class="{{ $selectedPractice->options->first()->overall_score >= 80 ? 'text-green-600' : ($selectedPractice->options->first()->overall_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $selectedPractice->options->first()->overall_score ?? 'N/A' }}/100</span>
                                </p>
                                <p><strong>Content Score:</strong> <span
                                        class="{{ $selectedPractice->options->first()->content_score >= 80 ? 'text-green-600' : ($selectedPractice->options->first()->content_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $selectedPractice->options->first()->content_score ?? 'N/A' }}/100</span>
                                </p>
                            </div>
                            <div>
                                <p><strong>Language Score:</strong> <span
                                        class="{{ $selectedPractice->options->first()->language_score >= 80 ? 'text-green-600' : ($selectedPractice->options->first()->language_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $selectedPractice->options->first()->language_score ?? 'N/A' }}/100</span>
                                </p>
                                <p><strong>Structure Score:</strong> <span
                                        class="{{ $selectedPractice->options->first()->structure_score >= 80 ? 'text-green-600' : ($selectedPractice->options->first()->structure_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $selectedPractice->options->first()->structure_score ?? 'N/A' }}/100</span>
                                </p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h4 class="font-medium">Errors:</h4>
                            @php
                                $errors = $selectedPractice->options->first()->errors;
                                $errors = is_string($errors) ? json_decode($errors, true) : $errors;
                            @endphp
                            @if (!empty($errors) && is_array($errors))
                                <div class="overflow-x-auto">
                                    <table class="w-full border-collapse border border-gray-300">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-300 p-2 text-left">Type</th>
                                                <th class="border border-gray-300 p-2 text-left">Original</th>
                                                <th class="border border-gray-300 p-2 text-left">Correction</th>
                                                <th class="border border-gray-300 p-2 text-left">Explanation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($errors as $error)
                                                <tr>
                                                    <td class="border border-gray-300 p-2">
                                                        {{ ucfirst($error['type']) }}</td>
                                                    <td class="border border-gray-300 p-2 text-red-700">
                                                        {{ $error['original'] }}</td>
                                                    <td class="border border-gray-300 p-2 text-green-700">
                                                        {{ $error['correction'] }}</td>
                                                    <td class="border border-gray-300 p-2">{{ $error['explanation'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>No errors found.</p>
                            @endif
                        </div>
                    </div>
                    <p class="mb-2"><strong>Overall Feedback:</strong></p>
                    <p class="mb-4 text-sm">
                        <span
                            class="whitespace-pre-wrap">{{ $selectedPractice->options->first()->overall_feedback ?? 'N/A' }}</span>
                    </p>
                </div>
            @else
                <p class="text-red-500">Practice details not available.</p>
            @endif
            <div class="mt-6 flex justify-end">
                <button wire:click="closePracticeModal"
                    class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Close
                </button>
            </div>
        </div>
    </x-popup>

    --}}

    <!-- Voice Practice Modal -->
    <x-popup wire:model="showVoicePracticeModal">
        <div class="p-6 max-h-[80vh] overflow-y-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Voice Practice Details</h3>
            @if ($selectedVoicePractice)
                @php
                    $evaluation = json_decode($selectedVoicePractice->evaluation, true);
                @endphp
                <div class="mt-2">
                    <p class="text-sm text-gray-500 mb-2">
                        <strong>Practice Item:</strong>
                        {{ ucfirst(str_replace('_', ' ', $selectedVoicePractice->interviewWritingPractice->question_type ?? 'N/A')) }}
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        <strong>Question:</strong>
                        <span class="whitespace-pre-wrap">{{ $evaluation['question'] ?? 'N/A' }}</span>
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        <strong>Prepared Answer:</strong>
                        <span class="whitespace-pre-wrap">{{ $evaluation['saved_answer'] ?? 'N/A' }}</span>
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        <strong>Your Answer (Transcribed):</strong>
                        <span
                            class="whitespace-pre-wrap">{{ $selectedVoicePractice->transcribed_text ?? 'N/A' }}</span>
                    </p>
                    <div class="mb-4">
                        <strong>Your Voice Recording:</strong>
                        <audio controls src="{{ $selectedVoicePractice->user_voice_url }}" class="mt-2 w-full"></audio>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-lg mb-2">Feedback</h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p><strong>Overall Score:</strong> <span
                                        class="{{ $selectedVoicePractice->overall_score >= 80 ? 'text-green-600' : ($selectedVoicePractice->overall_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $selectedVoicePractice->overall_score ?? 'N/A' }}/100</span>
                                </p>
                                <p><strong>Content Score:</strong> <span
                                        class="{{ $selectedVoicePractice->content_score >= 80 ? 'text-green-600' : ($selectedVoicePractice->content_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $selectedVoicePractice->content_score ?? 'N/A' }}/100</span>
                                </p>
                            </div>
                            <div>
                                <p><strong>Language Score:</strong> <span
                                        class="{{ $selectedVoicePractice->language_score >= 80 ? 'text-green-600' : ($selectedVoicePractice->language_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $selectedVoicePractice->language_score ?? 'N/A' }}/100</span>
                                </p>
                                <p><strong>Pronunciation Score:</strong> <span
                                        class="{{ $selectedVoicePractice->pronunciation_score >= 80 ? 'text-green-600' : ($selectedVoicePractice->pronunciation_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $selectedVoicePractice->pronunciation_score ?? 'N/A' }}/100</span>
                                </p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h4 class="font-medium">Errors:</h4>
                            @if (!empty($evaluation['errors']) && is_array($evaluation['errors']))
                                <div class="overflow-x-auto">
                                    <table class="w-full border-collapse border border-gray-300">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-300 p-2 text-left">Type</th>
                                                <th class="border border-gray-300 p-2 text-left">Original</th>
                                                <th class="border border-gray-300 p-2 text-left">Correction</th>
                                                <th class="border border-gray-300 p-2 text-left">Explanation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($evaluation['errors'] as $error)
                                                <tr>
                                                    <td class="border border-gray-300 p-2">
                                                        {{ ucfirst($error['type']) }}</td>
                                                    <td class="border border-gray-300 p-2 text-red-700">
                                                        {{ $error['original'] }}</td>
                                                    <td class="border border-gray-300 p-2 text-green-700">
                                                        {{ $error['correction'] }}</td>
                                                    <td class="border border-gray-300 p-2">{{ $error['explanation'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>No errors found.</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="mb-2"><strong>Comparison:</strong></p>
                        <p class="mb-4 text-sm">
                            <span class="whitespace-pre-wrap">{{ $evaluation['comparison'] ?? 'N/A' }}</span>
                        </p>
                        <p class="mb-2"><strong>Feedback:</strong></p>
                        <p class="mb-4 text-sm">
                            <span class="whitespace-pre-wrap">{{ $evaluation['feedback'] ?? 'N/A' }}</span>
                        </p>
                    </div>
                </div>
            @else
                <p class="text-red-500">Practice details not available.</p>
            @endif
            <div class="mt-6 flex justify-end">
                <button wire:click="closePracticeModal"
                    class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Close
                </button>
            </div>
        </div>
    </x-popup>
</div>
