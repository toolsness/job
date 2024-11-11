<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">AI Settings</h2>
        <div class="flex items-center space-x-3">
            <span class="text-sm font-medium text-gray-700">Developer Mode</span>
            <button type="button" wire:click="toggleDeveloperMode"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $developerMode ? 'bg-blue-600' : 'bg-gray-200' }}"
                role="switch" aria-checked="{{ $developerMode ? 'true' : 'false' }}">
                <span aria-hidden="true"
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $developerMode ? 'translate-x-5' : 'translate-x-0' }}"></span>
            </button>
        </div>
    </div>

    @if ($developerMode)
        <div class="mb-8 p-4 bg-yellow-50 border-l-4 border-yellow-400">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Developer Mode is active. You can now configure regional settings and advanced options.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if ($developerMode)
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 border border-gray-200">
            <form wire:submit.prevent="saveAIRegionSettings">
                <h3 class="text-xl font-semibold mb-4">AI Region Settings</h3>
                <div class="mb-4">
                    <label for="aiRegion" class="block mb-2">AI Region</label>
                    <select wire:model.live="aiRegion" id="aiRegion" class="w-full p-2 border rounded">
                        <option value="hongkong">Hong Kong</option>
                        <option value="japan">Japan</option>
                    </select>
                </div>

                @if ($aiRegion === 'japan')
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label for="japanProxyUrls.chat" class="block mb-2">Japan Chat Proxy URL</label>
                            <input type="url" wire:model.live="japanProxyUrls.chat" id="japanProxyUrls.chat"
                                class="w-full p-2 border rounded">
                        </div>

                        <div class="mb-4">
                            <label for="japanProxyUrls.speech" class="block mb-2">Japan Speech (TTS) Proxy
                                URL</label>
                            <input type="url" wire:model.live="japanProxyUrls.speech" id="japanProxyUrls.speech"
                                class="w-full p-2 border rounded">
                        </div>

                        <div class="mb-4">
                            <label for="japanProxyUrls.transcription" class="block mb-2">Japan Transcription
                                (Whisper) Proxy
                                URL</label>
                            <input type="url" wire:model.live="japanProxyUrls.transcription"
                                id="japanProxyUrls.transcription" class="w-full p-2 border rounded">
                        </div>

                        <div class="mb-4">
                            <label for="japanApiKey" class="block mb-2">Japan API Key</label>
                            <input type="password" wire:model.live="japanApiKey" id="japanApiKey"
                                class="w-full p-2 border rounded">
                        </div>
                    </div>
                @endif
                <div class="mt-4">

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save
                        AI Region
                        Settings</button>

                </div>
            </form>
        </div>
    @endif

    <h3 class="text-xl font-semibold mb-4">Custom Prompt Settings</h3>

    @foreach (['ai_voice_interview_test', 'ai_interview_answer_practice', 'ai_self_promotion_creator_cv'] as $segment)
        <div class="mb-8 border rounded-lg p-6 border-black shadow-lg shadow-black">
            <h4 class="text-lg font-extrabold mb-2">{{ ucfirst(str_replace('_', ' ', $segment)) }}</h4>

            <div class="mb-4">
                <label for="useCustomPrompts.{{ $segment }}" class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="useCustomPrompts.{{ $segment }}"
                        id="useCustomPrompts.{{ $segment }}" class="form-checkbox">
                    <span class="ml-2">Use Custom Prompts</span>
                </label>
            </div>

            @if ($useCustomPrompts[$segment] ?? false)
                <div class="mb-4">
                    <label for="aiModels.{{ $segment }}" class="block mb-2 font-semibold"><i
                            class="fas fa-robot"></i> AI Model</label>
                    <input type="text" wire:model.live="aiModels.{{ $segment }}"
                        id="aiModels.{{ $segment }}" class="w-full p-2 border rounded">
                    <button type="button" wire:click="$toggle('showAiModelsInfo.{{ $segment }}')"
                        class="mt-1 text-blue-500 hover:text-blue-700">
                        <i class="fas fa-info-circle"></i> Info
                    </button>
                </div>

                <div class="mb-4">
                    <label for="systemPrompts.{{ $segment }}" class="block mb-2 font-semibold"><i
                            class="fas fa-pencil"></i> System Prompt</label>
                    <textarea wire:model.live="systemPrompts.{{ $segment }}" id="systemPrompts.{{ $segment }}" rows="4"
                        class="w-full p-2 border rounded"></textarea>
                    <button type="button" wire:click="$toggle('showSystemPromptsInfo.{{ $segment }}')"
                        class="mt-1 text-blue-500 hover:text-blue-700">
                        <i class="fas fa-info-circle"></i> Info
                    </button>
                </div>
            @endif

            <button type="button" wire:click="saveCustomPromptSettings('{{ $segment }}')"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save
                {{ ucfirst(str_replace('_', ' ', $segment)) }} Settings</button>
        </div>

        <x-popup wire:model="showSystemPromptsInfo.{{ $segment }}">
            <div class="p-6 popup-content" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">System Prompt Information for {{ ucfirst(str_replace('_', ' ', $segment)) }}</h3>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="mb-4">The system prompt sets the context for the AI. Here's an example:</p>
                <pre class="bg-gray-100 p-2 rounded mb-4 text-pretty">You are an AI interview coach. To Evaluate an answer, you will be given a prepared answer and a spoken answer. The prepared answer will be compared to the spoken answer. The spoken answer will be compared to the prepared answer. Then you will evaluate the answer based on the criteria specified in the instructions.
Instructions:
    1. Don't give number in the language if prepared answer is not in the same language as the spoken answer.
    2. If prepared answer is not in the same language as the spoken answer, iclude this in the feedback and error.
    3. If spoken answer is off topic, include this in the feedback and error.
    4. If spoken answer is not grammatically correct, include this in the feedback and error.

Feedback Rules:
    1. Correct
      - If Spoken answer is match with the prepared answer
      - Minimum 90% to 100% of the words in the prepared answer are matched with the spoken answer
    2. Incorrect
      - If Spoken answer is not match with the prepared answer
      - If at least 0%-50% of words in the prepared answer are not match with the spoken answer
    3. Missing
      - If 51%-89% Spoken answer is not match with the prepared answer

Errors Rules:
    1. pronunciation, grammar, language, other etc.
    2. Error Type, other: Any other type of error that doesn't fit into the above categories, such as using filler words, being too vague, or going off-topic.
    3. Error Type, grammar: The type of grammar error, such as missing punctuation or extra punctuation.
    4. Error Type, language: The type of language error, if prepared answer is not in the same language as the spoken answer.
    5. Error Type, pronunciation: The type of pronunciation error, if prepared answer is not in the same language as the spoken answer.

Here are the datails about the candidate:

{candidateDetails}
</pre>
                <p class="mb-4">Available variables:</p>
                <ul class="list-disc pl-5 mb-4">
                    <li><strong>{question}</strong>: The interview question</li>
                    <li><strong>{userAnswer}</strong>: The user's answer to the question</li>
                    <li><strong>{practiceItem}</strong>: The type of practice item (e.g., self_introduction, motivation)</li>
                    <li><strong>{candidateDetails}</strong>: The candidate's personal and professional information</li>
                </ul>
            </div>
        </x-popup>
        <x-popup wire:model="showAiModelsInfo.{{ $segment }}">
            <div class="p-6 popup-content">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">AI Models Information</h3>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="mb-4">Here's a list of available AI models and their pricing:</p>

                <table class="w-full border-collapse border border-gray-300 mb-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-2">Model</th>
                            <th class="border border-gray-300 p-2">Input Price (per 1M tokens)</th>
                            <th class="border border-gray-300 p-2">Output Price (per 1M tokens)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2">gpt-4o</td>
                            <td class="border border-gray-300 p-2">$2.50</td>
                            <td class="border border-gray-300 p-2">$10.00</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">gpt-4o-mini-2024-07-18</td>
                            <td class="border border-gray-300 p-2">$0.150</td>
                            <td class="border border-gray-300 p-2">$0.600</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">gpt-4o-mini</td>
                            <td class="border border-gray-300 p-2">$0.150</td>
                            <td class="border border-gray-300 p-2">$0.600</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">gpt-4-turbo</td>
                            <td class="border border-gray-300 p-2">$10.00</td>
                            <td class="border border-gray-300 p-2">$30.00</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2">gpt-3.5-turbo</td>
                            <td class="border border-gray-300 p-2">$0.50</td>
                            <td class="border border-gray-300 p-2">$1.50</td>
                        </tr>
                        <!-- Add more rows for other models as needed -->
                    </tbody>
                </table>

                <p class="mb-2">Notes:</p>

                <p>For the most accurate and up-to-date pricing information, please visit the <a
                        href="https://openai.com/api/pricing/" target="_blank"
                        class="text-blue-600 hover:underline">OpenAI Pricing Page</a>.</p>
            </div>
        </x-popup>
    @endforeach

    <style>
        .popup-content {
            max-height: 70vh;
            overflow-y: auto;
        }

        .switch-toggle {
            transition: background-color 0.3s ease;
        }

        .switch-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        .switch-toggle[aria-checked="true"] {
            background-color: #2563eb;
        }

        .switch-toggle[aria-checked="false"] {
            background-color: #e5e7eb;
        }
    </style>
</div>
