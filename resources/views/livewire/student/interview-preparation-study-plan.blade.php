<div>
    <div class="font-sans bg-gray-100">
        <div class="container px-4 py-8 mx-auto">
            <h1 class="mb-6 text-2xl font-bold text-center">Interview Preparation Study Plan</h1>

            <div class="w-full max-w-4xl mx-auto border border-2 border-gray-700 rounded-lg shadow-md my-8">
                <!-- Card Title -->
                <div class="px-6 py-3 bg-[#3CB2E3]/40 rounded-t-lg border-b">
                    <h4 class="text-lg font-semibold text-black">{{ Auth::user()->name }}'s Learning Record</h4>
                </div>

                <div class="grid grid-cols-3 gap-4 px-6 py-4 bg-white text-center">
                    <!-- Previous Task -->
                    <div class="flex flex-col">
                        <p class="text-lg font-semibold text-gray-600 mb-2">Previous Task</p>
                        <div class="py-2 border border-2 border-gray-700 rounded-lg flex-1 flex flex-col justify-center">
                            @if ($previousTask)
                                <p class="text-sm font-medium text-gray-600">{{ $previousTask['date'] ?? '' }}</p>
                                <p class="text-lg font-semibold text-black">【{{ $previousTask['name'] }}】</p>
                                <a href="{{ $previousTask['url'] }}"
                                    class="text-sm font-medium text-blue-600 hover:underline">Go to task</a>
                            @else
                                <p class="text-sm font-medium text-gray-600">No previous task</p>
                            @endif
                        </div>
                    </div>

                    <!-- Next Task -->
                    <div class="flex flex-col">
                        <p class="text-lg font-semibold text-gray-600 mb-2">Next Task</p>
                        <div
                            class="py-2 border border-2 border-gray-700 rounded-lg flex-1 flex flex-col justify-center">
                            @if ($nextTask)
                                <p class="text-lg font-semibold text-black">{{ $nextTask['name'] }}</p>
                                <a href="{{ $nextTask['url'] }}"
                                    class="text-sm font-medium text-blue-600 hover:underline">Start task</a>
                            @else
                                <p class="text-sm font-medium text-gray-600">All tasks completed</p>
                            @endif
                        </div>
                    </div>

                    <!-- Total Study Hours -->
                    <div class="flex flex-col">
                        <p class="text-lg font-semibold text-gray-600 mb-2">Total Study Hours</p>
                        <div
                            class="py-2 border border-2 border-gray-700 rounded-lg flex-1 flex flex-col justify-center">
                            <p class="text-lg font-semibold text-black">Total: {{ $totalStudyTime }}</p>
                            <p class="text-lg font-semibold text-black">Practice: {{ $practiceStudyTime }}</p>
                            <p class="text-lg font-semibold text-black">Writing: {{ $writingStudyTime }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="w-full max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-md">
                <h2 class="mb-4 ml-4 text-lg font-semibold"><i class="fa-solid fa-circle-question fa-lg"></i> Interview
                    Preparation Study Plan (How to Progress)</h2>
                <p class="mb-4 text-sm text-gray-600">You will study according to the learning curriculum. You will
                    complete the five learning tasks one by one in order. After completing each learning task, you will
                    have an interview with your teacher. Once you have completed the mock interview learning task,
                    consult with your teacher and apply for a company interview.</p>

                <div class="space-y-4">
                    @foreach ($progressData as $task)
                        <a href="{{ $task['url'] ?? '#' }}" class="block">
                            <div class="flex items-center bg-gray-100 rounded-r rounded-full p-3 mb-4">
                                <div
                                    class="flex items-center justify-center w-12 h-12 mr-4 text-2xl font-semibold text-white bg-blue-500 border rounded-full shadow-lg">
                                    {{ $task['sl'] }}</div>
                                <div class="flex-grow">
                                    <div class="flex items-center">
                                        <span
                                            class="font-semibold text-gray-700 mr-4 w-1/4 truncate">{{ $task['name'] }}</span>
                                        <div class="flex-grow mx-4 w-3/5">
                                            <div class="w-full bg-white border border-gray-700 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full"
                                                    style="width: {{ $task['percentage'] }}%;"></div>
                                            </div>
                                        </div>
                                        <div class="w-1/5 text-right">
                                            <div class="text-sm text-gray-600">Last Updated</div>
                                            <div class="text-sm text-gray-600">{{ $task['date'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('home') }}"
                    class="px-4 py-2 text-white transition duration-300 bg-blue-500 rounded hover:bg-blue-600">Return to
                    TOP page</a>
            </div>
        </div>
    </div>
</div>
