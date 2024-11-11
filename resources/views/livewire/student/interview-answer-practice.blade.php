<div class="flex justify-center items-center bg-gray-50 w-full max-w-4xl mx-auto rounded-lg">
    <div wire:poll.10s.keep-alive="updateStudyTime" class="hidden"></div>
    <div class="bg-white rounded-lg p-16 w-full">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-semibold pb-5">Interview Practice</h1>
            <p class="px-20 text-left">The AI trainer will conduct interview practice for you. And the passing rate is
                about 80%, please visit <a class="underline hover:bg-gray-300"
                    href="{{ route('interview-answer.writing') }}"><strong>Interview Answer Practice Section</strong></a>
                for more practice if you feel unwilling to pass.</p>
        </div>

        <div class="mx-32">
            @if (!$aiQuestion && !$showResults)
                <!-- Interviewer Placeholder -->
                <div class="text-center mb-6">
                    <img src="{{ asset('interviewer-placeholder.png') }}" alt="Interviewer" class="mx-auto w-1/4">
                    <p class="mt-4 text-lg font-medium">Welcome! Let's prepare for your interview.</p>
                    <p class="text-sm text-gray-600">Follow these steps:</p>
                    <ol class="text-sm text-left list-decimal list-inside mt-2">
                        <li>Choose to select an existing vacancy or generate job titles</li>
                        <li>Select a specific vacancy or job title</li>
                        <li>Click "Start Interview" when you're ready</li>
                    </ol>
                </div>

                <!-- Vacancy Selection -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Select a Vacancy</h2>
                    <div class="mb-4">
                        <label class="block mb-2">
                            <input type="radio" wire:model.live="vacancySelectionMethod" value="existing"> Choose From
                            Existing Jobs
                        </label>
                        <label class="block mb-2">
                            <input type="radio" wire:model.live="vacancySelectionMethod" value="generate"> Generate
                            Job Titles As Per Your Selected Job Category
                        </label>
                    </div>

                    @if ($vacancySelectionMethod === 'existing')
                        <select wire:model.live="selectedVacancy" class="w-full p-2 border rounded">
                            <option value="">Select a vacancy</option>
                            @foreach ($vacancies as $vacancy)
                                <option value="{{ $vacancy->id }}">{{ $vacancy->job_title }} -
                                    {{ $vacancy->company->name }}</option>
                            @endforeach
                        </select>
                    @endif
                    @if ($vacancySelectionMethod === 'generate')
                        <div class="space-y-4">
                            <select wire:model.live="selectedVacancyCategory" class="w-full p-2 border rounded">
                                <option value="">Select a vacancy category</option>
                                @foreach ($vacancyCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <div wire:loading.delay wire:target="selectedVacancyCategory"
                                class="flex items-center justify-center mt-2">
                                <div
                                    class="animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-blue-500 mr-2">
                                </div>
                                <span>Generating job titles...</span>
                            </div>

                            @if ($generatedJobTitles)
                                <select wire:model.live="selectedJobTitle" class="w-full p-2 border rounded">
                                    <option value="">Select a job title</option>
                                    @foreach ($generatedJobTitles as $jobTitle)
                                        <option value="{{ $jobTitle }}">{{ $jobTitle }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endif

                    <button wire:click="startInterviewCountdown"
                        class="mt-4 bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-lg hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105">
                        Start Interview
                    </button>

                    <x-popup wire:model="showStartInterviewPopup">
                        <div class="text-center">
                            <h3 class="text-lg font-medium mb-4">Preparing Your Interview</h3>
                            <div x-data="{ countdown: 3 }" x-init="$watch('$wire.showStartInterviewPopup', value => {
                                if (value) {
                                    let timer = setInterval(() => {
                                        if (countdown > 0) countdown--;
                                        if (countdown === 0) {
                                            $wire.startInterview();
                                            clearInterval(timer);
                                        }
                                    }, 1000);
                                }
                            })">
                                <p class="text-3xl font-bold mb-4" x-text="countdown === 0 ? 'Starting...' : countdown">
                                </p>
                                <p>Get ready for your interview!</p>
                            </div>
                        </div>
                    </x-popup>
                </div>
            @endif

            @if ($aiQuestion && !$showResults)

                <!-- Interview Section -->
                <div class="p-6 rounded-md border border-gray-200 text-center space-y-4">
                    <div class="w-1/4 mx-auto">
                        <img src="{{ asset('interviewer-placeholder.png') }}" alt="Character" class="mx-auto">
                    </div>
                    <div class="bg-gray-200 p-8 relative flex items-center justify-center">
                        <p class="text-normal font-medium text-center">{{ $aiQuestion }}</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-10">
                    <div class="flex justify-between items-center bg-gray-100 p-6 rounded-lg shadow-md">
                        @php
                            $sections = [
                                'self_introduction' => 'Self Introduction',
                                'motivation' => 'Motivation',
                                'advantages_disadvantages' => 'Strengths and Weaknesses',
                                'future_plans' => 'Future Plans',
                                'questions_to_companies' => 'Questions To Company',
                            ];
                        @endphp
                        @foreach ($sections as $key => $section)
                            <div class="text-center flex flex-col items-center">
                                <div
                                    class="w-16 h-16 mb-2 rounded-full flex items-center justify-center transition-all duration-300 ease-in-out
                    {{ $currentQuestionIndex === array_search($key, array_keys($sections))
                        ? 'bg-blue-500 animate-pulse'
                        : (array_search($key, array_keys($sections)) < $currentQuestionIndex
                            ? 'bg-green-500'
                            : 'bg-gray-300') }}">
                                    @if (array_search($key, array_keys($sections)) < $currentQuestionIndex)
                                        <i class="fas fa-check text-white text-2xl"></i>
                                    @else
                                        <span class="text-white text-xl font-bold">{{ $loop->iteration }}</span>
                                    @endif
                                </div>
                                <p class="text-xs font-medium text-gray-600 max-w-[80px] leading-tight">
                                    {{ $section }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Answer Section -->
                <div class="p-4 border border-gray-300 rounded-md bg-white space-y-4 mt-4">
                    <h3 class="font-medium text-lg">Your Answer</h3>
                    <textarea wire:model="answer" rows="5" class="w-full p-3 border rounded" placeholder="Type your answer here..."></textarea>
                </div>

                <div class="relative" wire:ignore>
                    <button wire:click="evaluate" wire:loading.attr="disabled" wire:target="evaluate"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-lg hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105 my-5 mx-auto block">
                        <span wire:loading.remove wire:target="evaluate">Submit Answer</span>
                        <span wire:loading wire:target="evaluate">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            @endif

            @if ($showResults)
                <!-- Practice Results -->
                <div class="mt-8">
                    <h2 class="text-2xl font-semibold mb-4">Practice Results</h2>
                    @foreach ($practiceResults as $index => $result)
                        <div class="mb-8 p-6 border border-gray-300 rounded-md bg-white">
                            <h3 class="text-xl font-semibold mb-2">Question {{ $index + 1 }}:
                                {{ $result['question'] }}</h3>
                            <div class="mb-4">
                                <h4 class="font-medium">Your Answer:</h4>
                                <p class="whitespace-pre-wrap">{{ $result['answer'] }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="font-medium">Improved Answer:</h4>
                                <p class="whitespace-pre-wrap">{{ $result['evaluation']['improved_answer'] }}</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="font-medium">Evaluation:</h4>
                                <p><strong>Overall Score:</strong>
                                    {{ $result['evaluation']['overall_score'] ?? 'N/A' }}/100</p>
                                <p><strong>Content Score:</strong>
                                    {{ $result['evaluation']['content_score'] ?? 'N/A' }}/100</p>
                                <p><strong>Language Score:</strong>
                                    {{ $result['evaluation']['language_score'] ?? 'N/A' }}/100</p>
                                <p><strong>Structure Score:</strong>
                                    {{ $result['evaluation']['structure_score'] ?? 'N/A' }}/100</p>
                            </div>
                            <div class="mb-4">
                                <h4 class="font-medium">Feedback:</h4>
                                <p class="whitespace-pre-wrap">
                                    {{ $result['evaluation']['overall_feedback'] ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <h4 class="font-medium">Errors:</h4>
                                <table class="w-full border-collapse border border-gray-300">
                                    <thead>
                                        <tr>
                                            <th class="border border-gray-300 p-2">Type</th>
                                            <th class="border border-gray-300 p-2">Original</th>
                                            <th class="border border-gray-300 p-2">Correction</th>
                                            <th class="border border-gray-300 p-2">Explanation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($result['evaluation']['errors'] ?? [] as $error)
                                            <tr>
                                                <td class="border border-gray-300 p-2">{{ $error['type'] }}</td>
                                                <td class="border border-gray-300 p-2 text-red-700">{{ $error['original'] }}</td>
                                                <td class="border border-gray-300 p-2 text-green-700">{{ $error['correction'] }}</td>
                                                <td class="border border-gray-300 p-2">{{ $error['explanation'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Buttons -->

            <div class="mt-16 flex flex-col sm:flex-row justify-center items-center gap-4 sm:gap-6">
                <a href="{{ route('interview-answer.writing') }}"
                    class="w-full sm:w-auto border border-black hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 text-center text-sm sm:text-base">
                    Back to Practice Interview Answer
                </a>

                <a href="{{ route('home') }}"
                    class="w-full sm:w-auto border border-black hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 text-center text-sm sm:text-base">
                    Return to TOP
                </a>

                <a href="{{ route('interview-answer.evaluation') }}"
                    class="w-full sm:w-auto border border-black hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 text-center text-sm sm:text-base">
                    View All Results
                </a>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    {{-- <div wire:loading wire:target="generateJobTitles, startInterview, evaluate"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
            <p class="mt-4 text-center text-gray-700">Processing...</p>
        </div>
    </div> --}}
</div>
