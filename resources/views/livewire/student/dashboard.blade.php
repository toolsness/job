<div>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .dashboard-content {
            position: relative;
            z-index: 1;
        }
    </style>
    <div class="font-sans">
        <div class="container p-6 mx-auto dashboard-content">
            <div class="p-6 transition-colors duration-100 bg-white border border-black rounded-lg shadow-xl">
                @auth('web')
                    @if (Auth::user()->user_type === 'Student')
                        <div class="grid grid-cols-3">
                            <div class="px-4"></div>
                            <button
                                class="px-6 py-2 font-bold text-black transition duration-300 bg-white border border-black rounded-md shadow-lg md:grid-cols-4 hover:text-white hover:bg-green-700"
                                wire:click="startJobHunting" wire:loading.attr="disabled" wire:loading.remove>Start Job
                                Hunting</button>
                            <span wire:loading wire:target="startJobHunting">
                                <span class="font-bold text-green-700 "><i class="fa fa-spinner fa-spin"></i> <span
                                        class='font-extrabold'>
                                        Processing Start...</span>
                                </span>
                            </span>
                            <div class="px-4"></div>
                        </div>
                    @elseif (Auth::user()->user_type === 'Candidate')
                        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4 overflow-hidden">
                            @foreach ($progressData as $task)
                                <div class="text-center" x-data="{ percentage: {{ $task['percentage'] }} }">
                                    <svg class="w-24 h-24 mx-auto mb-2 progress-ring" viewBox="0 0 36 36">
                                        <circle cx="18" cy="18" r="16" fill="none"
                                            class="text-gray-300 stroke-current" stroke-width="3.6"></circle>
                                        <circle cx="18" cy="18" r="16" fill="none"
                                            class="stroke-current text-lime-400" stroke-width="3.6"
                                            :stroke-dasharray="2 * Math.PI * 16"
                                            :stroke-dashoffset="2 * Math.PI * 16 * (1 - percentage / 100)" x-cloak></circle>
                                    </svg>
                                    <p class="font-semibold">{{ $task['name'] }}</p>
                                    <p x-text="`${percentage}% Complete`"></p>
                                </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-6">
                            <div class="p-4 border border-gray-300 rounded-lg">
                                <h3 class="font-semibold mb-2">Previous Task</h3>
                                @if ($previousTask)
                                    <a href="{{ $previousTask['url'] }}"
                                        class="text-blue-600 hover:underline">{{ $previousTask['name'] }}</a>
                                @else
                                    <p class="text-gray-500">No previous task</p>
                                @endif
                            </div>
                            <div class="p-4 border border-gray-300 rounded-lg">
                                <h3 class="font-semibold mb-2">Next Task</h3>
                                @if ($nextTask)
                                    <a href="{{ $nextTask['url'] }}"
                                        class="text-blue-600 hover:underline">{{ $nextTask['name'] }}</a>
                                @else
                                    <p class="text-gray-500">All tasks completed</p>
                                @endif
                            </div>
                            <div class="p-4 border border-gray-300 rounded-lg">
                                <h3 class="font-semibold mb-2">Total Study Time</h3>
                                <p>Total: {{ $totalStudyTime }}</p>
                                <p>Practice: {{ $practiceStudyTime }}</p>
                                <p>Writing: {{ $writingStudyTime }}</p>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
