<div class="flex justify-center items-center bg-gray-50 w-full max-w-7xl mx-auto rounded-lg">
    <div class="bg-white rounded-lg p-16 w-full">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-semibold pb-5">CV (self-promotion) creation</h1>
            <p class="px-20 text-left">Create a self-promotional statement to include in your resume (CV). Enter self-promotion and press "Generate AI" button. AI will improve the text based on your profile details. The information will be saved in your resume and can be edited in the future.</p>
        </div>

        <!-- Candidate Details Summary -->
        <div class="mb-6 p-4 bg-gray-100 rounded-lg">
            <h2 class="font-semibold mb-2">Your Profile Summary</h2>
            <p><strong>Name:</strong> {{ $candidate->name }}</p>
            <p><strong>Education:</strong> {{ $candidate->last_education }} at {{ $candidate->college }}</p>
            <p><strong>Japanese Level:</strong> {{ $candidate->japanese_language_qualification }}</p>
            <p><strong>Desired Job:</strong> {{ $candidate->desiredJobType->name }}</p>
            <p><strong>Qualifications:</strong>
                @foreach($candidate->qualifications->groupBy('qualificationCategory.name') as $category => $quals)
                    <span class="font-semibold">{{ $category }}:</span> {{ $quals->pluck('qualification_name')->implode(', ') }}<br>
                @endforeach
            </p>
        </div>

        <!-- Input Section -->
        <div class="grid grid-cols-3 gap-6 mb-6">
            <!-- Self-promotion Input -->
            <div class="col-span-2 flex flex-col border-2 border-gray-300 rounded">
                <textarea wire:model="selfPromotion" rows="5" class="w-full p-3 border-0 rounded flex-grow" placeholder="Please enter your self-promotional text here"></textarea>
                <button wire:loading.attr="disabled" wire:target="generateAI" wire:loading.remove wire:click="generateAI" class="m-2 px-4 py-2 bg-blue-500 text-white rounded self-end">AI Generate</button>
                <div wire:loading wire:target="generateAI">
                    <span class="font-bold text-blue-500"><i class="fa fa-spinner fa-spin"></i> Generating...</span>
                </div>
            </div>

            <!-- Answer History -->
            <div class="p-3 border-2 rounded bg-white flex flex-col">
                <h2 class="font-semibold mb-2">Answer History</h2>
                @forelse($histories as $history)
                    <div class="mb-2">
                        <p><strong>{{ $history->created_at->format('F j, Y H:i') }}</strong></p>
                        <p>{{ Str::limit($history->user_text, 100) }}</p>
                        <button wire:click="showHistory({{ $history->id }})" class="text-blue-500 hover:underline">View Details</button>
                        <hr class="my-2">
                    </div>
                @empty
                    <p>No history available.</p>
                @endforelse
                {{ $histories->links() }}
            </div>
        </div>

        <!-- AI-generated Text Section -->
        <div class="w-full border-2 border-gray-300 rounded mt-16 flex flex-col">
            <textarea wire:model="aiGeneratedText" rows="5" class="w-full p-3 border-0 whitespace-pre-wrap" placeholder="AI-generated text will be displayed here"></textarea>

            <!-- Ensure the button is aligned to the right -->
            <div class="flex justify-end">
                <button wire:click="save" class="m-2 px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="mt-16 flex justify-around items-center">
            <a href="{{ route('interview-preparation-study-plan') }}" class="px-4 py-2 bg-gray-200 text-black rounded"><i class="fas fa-arrow-left mr-2"></i> Back to Interview Preparation</a>
            <a href="{{ route('interview-answer.writing') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Create interview answer <i class="fas fa-arrow-right ml-2"></i></a>
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('home') }}" class="px-4 py-2 text-gray-700 transition duration-300 bg-gray-200 rounded hover:bg-gray-300">Return to Previous page</a>
        </div>
    </div>

    <!-- History Modal -->
    @if($showHistoryModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="history-modal">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Self-Promotion History Details</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 whitespace-pre-wrap">
                        <strong>User Text:</strong> {{ $selectedHistory->user_text ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-2 whitespace-pre-wrap">
                        <strong>AI Generated Text:</strong> {{ $selectedHistory->ai_generated_text ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-2">
                        <strong>Created At:</strong> {{ $selectedHistory->created_at->format('F j, Y H:i') ?? 'N/A' }}
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="closeHistoryModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
