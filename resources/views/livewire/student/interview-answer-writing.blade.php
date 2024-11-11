<div class="flex justify-center items-center bg-gray-50 w-full max-w-7xl mx-auto rounded-lg">
    <div wire:poll.10s="updateStudyTime"></div>
    <div class="bg-white rounded-lg p-16 w-full">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-semibold pb-5">Interview Answer Writing</h1>
            <p class="px-20 text-left">Prepare and practice interview answers for each interview question. After entering
                your answer, please press the Generate by AI button. The AI will evaluate and improve your answer. The
                generated AI answers will be saved as history. If you want to look back at the answer history, click
                View Details in the
                Answer History section.</p>
        </div>

        <!-- Language Selection -->
        <div class="mb-4">
            <label for="language" class="block text-sm font-medium text-gray-700">Select Language:</label>
            <select wire:model="selectedLanguage" wire:change="changeLanguage($event.target.value)" id="language"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @foreach ($supportedLanguages as $code => $name)
                    <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Practice Item -->
        <select wire:model.live="practiceItem" class="border-2 rounded border-gray-500 p-2 w-1/4 my-4">
            <option value="">Please select a practice item</option>
            @foreach ($questions as $key => $questionSet)
                <option value="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</option>
            @endforeach
        </select>

        <!-- Collapsible Voice Practice Selection -->
        <div class="mt-8 mb-4">
            <button wire:click="toggleVoicePracticeCollapse" class="flex items-center text-xl font-semibold mb-2">
                <i class="fas fa-chevron-{{ $voicePracticeCollapsed ? 'down' : 'up' }} mr-2"></i>
                <span class="hover:underline">Show Selected Answers for Voice Practice</span><span
                    class="text-sm @if ($voicePracticeOptions->count() == 5) text-green-500 @else text-red-500
                    @endif">@if (true) ({{ $voicePracticeOptions->count() }}) @else (0)
                    @endif</span>
            </button>

            @if (!$voicePracticeCollapsed)
                @if ($histories->isEmpty())
                    <p class="text-sm text-gray-500">No answer selected for this question type.</p>
                @else
                    <div class="mt-4">
                        @foreach ($voicePracticeOptions as $questionType => $practices)
                            <div class="mb-4 p-4 border border-gray-300 rounded">
                                <h3 class="font-semibold">{{ ucfirst(str_replace('_', ' ', $questionType)) }}</h3>
                                @if ($practices->isNotEmpty())
                                    @php $practice = $practices->first(); @endphp
                                    <p class="mt-2"><strong>User Answer:</strong>
                                        {{ Str::limit($practice->user_answer, 100) }}</p>
                                    <p class="mt-2"><strong>Improved Answer:</strong>
                                        {{ Str::limit($practice->improved_answer, 100) }}</p>
                                    <p class="text-sm text-gray-600">Created:
                                        {{ $practice->created_at->format('F j, Y H:i') }}</p>
                                        @if ($practice->ai_voice_url)
                                        <audio controls src="{{ $practice->ai_voice_url }}" class="mt-2 w-full">
                                        </audio>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-500">No answer selected for this question type.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        <!-- Input Section -->
        <div class="grid grid-cols-3 gap-6 mb-6">
            <!-- User Input -->
            <div class="col-span-2 rounded-lg border-2 border-gray-500 flex flex-col overflow-hidden">
                <textarea wire:model.live="userAnswer" rows="5" class="w-full p-3 border-0 rounded flex-grow"
                    placeholder="{{ $questions[$practiceItem][$selectedLanguage] ?? 'Please select a practice item' }}"></textarea>
                <button wire:click="generateAI" class="m-2 px-4 py-2 bg-blue-500 text-white rounded self-end"
                    wire:loading.attr="disabled" wire:target="generateAI">
                    <span wire:loading.remove wire:target="generateAI">AI Generate</span>
                    <span wire:loading wire:target="generateAI"><i class="fa fa-spinner fa-spin"></i> Generating...</span>
                </button>
            </div>

            <!-- Answer History -->
            <div class="mt-8 mb-4">
                @if ($histories->isEmpty())
                    <h2 class="text-center text-xl font-semibold mb-2">No History</h2>
                @else
                    <h2 class="text-xl font-semibold mb-2">Answer History</h2>
                @endif

                @foreach ($histories as $history)
                    <div class="mb-4 p-4 border border-gray-300 rounded">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $history->question_type)) }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $history->created_at->format('F j, Y H:i') }}</p>
                            </div>
                            <div>
                                @if ($history->selected_for_voice_practice)
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                        Voice Practice
                                    </span>
                                @endif
                            </div>
                        </div>
                        <p class="mt-2">{{ Str::limit($history->user_answer, 100) }}</p>
                        <div class="mt-2 flex justify-between items-center">
                            <button wire:click="showHistory({{ $history->id }})"
                                class="text-blue-500 hover:underline">
                                View Details
                            </button>
                            <button wire:click="toggleVoicePracticeSelection({{ $history->id }})"
                                class="px-4 py-2 rounded text-xs {{ $history->selected_for_voice_practice ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }}">
                                {{ $history->selected_for_voice_practice ? 'Unselect from Voice Practice' : 'Select for Voice Practice' }}
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                @if ($histories->hasPages())
                    <div class="flex items-end my-2">
                        @if (!$histories->onFirstPage())
                            <!-- First Page Link -->
                            <a class="mx-1 px-2 py-2 border-2 border-black text-black text-xs font-bold text-center hover:text-white hover:bg-blue-900 hover:border-blue-900 rounded-lg cursor-pointer"
                               wire:click="gotoPage(1)" title="First Page">
                                ⏪ First
                            </a>
                            @if ($histories->currentPage() > 2)
                                <!-- Previous Page Link -->
                                <a class="mx-1 px-2 py-2 border-2 border-black text-black text-xs font-bold text-center hover:text-white hover:bg-blue-900 hover:border-blue-900 rounded-lg cursor-pointer"
                                   wire:click="previousPage" title="Previous Page">
                                    ◀️ Previous
                                </a>
                            @endif
                        @endif

                        <!-- Pagination Elements -->
                        @foreach ($elements as $element)
                            <!-- Array Of Links -->
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <!-- Use three dots when current page is greater than 3. -->
                                    @if ($histories->currentPage() > 3 && $page === 2)
                                        <div class="text-blue-800 mx-1">
                                            <span class="font-bold">.</span>
                                            <span class="font-bold">.</span>
                                            <span class="font-bold">.</span>
                                        </div>
                                    @endif

                                    <!-- Show active page two pages before and after it. -->
                                    @if ($page == $histories->currentPage())
                                        <span class="mx-1 px-4 py-2 border-2 border-blue-400 bg-blue-400 text-white font-bold text-center hover:bg-blue-800 hover:border-blue-800 rounded-lg cursor-pointer">{{ $page }}</span>
                                    @elseif ($page === $histories->currentPage() + 1 || $page === $histories->currentPage() + 2 || $page === $histories->currentPage() - 1 || $page === $histories->currentPage() - 2)
                                        <a class="mx-1 px-4 py-2 border-2 border-blue-900 text-blue-900 font-bold text-center hover:text-blue-400 rounded-lg cursor-pointer"
                                           wire:click="gotoPage({{ $page }})">{{ $page }}</a>
                                    @endif

                                    <!-- Use three dots when current page is away from end. -->
                                    @if ($histories->currentPage() < $histories->lastPage() - 2 && $page === $histories->lastPage() - 1)
                                        <div class="text-blue-800 mx-1">
                                            <span class="font-bold">.</span>
                                            <span class="font-bold">.</span>
                                            <span class="font-bold">.</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        <!-- Next Page Link -->
                        @if ($histories->hasMorePages())
                            @if ($histories->lastPage() - $histories->currentPage() >= 2)
                                <a class="mx-1 px-2 py-2 border-2 border-black text-black text-xs font-bold text-center hover:text-white hover:bg-blue-900 hover:border-blue-900 rounded-lg cursor-pointer"
                                   wire:click="nextPage" rel="next" title="Next Page">
                                    Next ▶
                                </a>
                            @endif
                            <a class="mx-1 px-2 py-2 border-2 text-black border-black text-xs font-bold text-center hover:text-white hover:bg-blue-900 hover:border-blue-900 rounded-lg cursor-pointer"
                               wire:click="gotoPage({{ $histories->lastPage() }})" title="Last Page">
                                Last ⏩
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- AI-generated Text Section -->
        <div class="w-full border-2 rounded-lg border-gray-500 mt-16 flex flex-col overflow-hidden">
            <textarea wire:model="aiGeneratedAnswer" rows="5" class="w-full p-3 border-0 whitespace-pre-wrap" readonly
                placeholder="AI-generated improved answer will be displayed here"></textarea>
            @if ($aiGeneratedAnswer)
                <audio controls src="{{ $this->ai_voice_url }}" class="mt-2 w-full"></audio>
            @endif
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-16 flex justify-around items-center">
            <a href="{{ route('interview-preparation-study-plan') }}"
                class="px-4 py-2 border-black text-black border rounded hover:bg-black hover:text-white"><i class="fas fa-arrow-left mr-2"></i> Back to
                Interview Preparation</a>
            {{-- <a href="{{ route('interview-answer.practice') }}"
                class="px-4 py-2 bg-blue-500 text-white rounded">Practice
                Interview<i class="fas fa-arrow-right ml-2"></i></a> --}}
            <a href="{{ route('interview-practice.voice') }}" class="px-4 py-2 border-black text-black border rounded hover:bg-black hover:text-white">
                Practice Interview <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('home') }}"
                class="px-4 py-2 border-black text-black border rounded hover:bg-black hover:text-white"><i class="fas fa-home mr-2"></i> Return to
                TOP page</a>
        </div>
    </div>

    <!-- History Modal -->
    <x-popup wire:model="showModal">
        <div class="p-6 max-h-[80vh] overflow-y-auto">
            @if (!is_null($selectedHistory))
                <h3 class="text-lg font-medium text-gray-900 mb-4">Answer History Details</h3>
                <div class="mt-2">
                    <p class="font-bold">Question:</p>
                    <p>{{ $selectedHistory->question }}</p>
                    <p class="font-bold mt-4">Your Answer:</p>
                    <p>{{ $selectedHistory->user_answer }}</p>
                    <p class="font-bold mt-4">Improved Answer:</p>
                    <p>{{ $selectedHistory->improved_answer }}</p>
                    @if ($selectedHistory->ai_voice_url)
                        <audio controls src="{{ $selectedHistory->ai_voice_url }}" class="mt-2 w-full"></audio>
                    @endif
                    <div class="mt-4">
                        <p class="font-bold">Scores:</p>
                        <p>Overall: {{ $selectedHistory->overall_score }}/100</p>
                        <p>Content: {{ $selectedHistory->content_score }}/100</p>
                        <p>Language: {{ $selectedHistory->language_score }}/100</p>
                        <p>Structure: {{ $selectedHistory->structure_score }}/100</p>
                    </div>
                    <p class="font-bold mt-4">Errors:</p>
                    @if (count(json_decode($selectedHistory->errors, true)) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full mt-2 border-collapse border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2">Type</th>
                                        <th class="border border-gray-300 px-4 py-2">Original</th>
                                        <th class="border border-gray-300 px-4 py-2">Correction</th>
                                        <th class="border border-gray-300 px-4 py-2">Explanation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (json_decode($selectedHistory->errors, true) as $error)
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2">{{ ucfirst($error['type']) }}
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-red-500">
                                                {{ $error['original'] }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-green-500">
                                                {{ $error['correction'] }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $error['explanation'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No errors found.</p>
                    @endif
                    <p class="font-bold mt-4">Overall Feedback:</p>
                    <p>{{ $selectedHistory->overall_feedback }}</p>
                </div>
            @else
                <p>No history selected.</p>
            @endif
            <div class="mt-6">
                <button @click="$dispatch('close')"
                    class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Close
                </button>
            </div>
        </div>
    </x-popup>
    <script>
        function playTTS(elementId) {
            const text = document.getElementById(elementId).value;
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = '{{ $selectedLanguage === 'japanese' ? 'ja-JP' : 'en-US' }}';
            speechSynthesis.speak(utterance);
        }
    </script>
</div>
