<div>
    <div class="font-sans bg-gray-100">
        <div class="container max-w-3xl px-4 py-8 mx-auto">
            <!-- Video Section -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Orientation</h2>
                <p class="mb-4">
                    Watch the video to learn about the content and study methods of the five learning tasks.
                    You can watch it as many times as you like. Once you understand it well, click the Complete button.
                </p>

                <div class="mt-4 text-center">
                    <div class="flex justify-center">
                        <iframe class="w-full" width="560" height="315"
                            src="https://www.youtube.com/embed/2hWgDOr3Pdc?si=hpu59OsGHyFkFV6v" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                    @if ($hasUnderstoodInterviewPreparation)
                    <div class="mt-4">
                       <p class="text-sm"><i class="fa-solid fa-circle-check fa-lg"></i> You already understand how to study for an interview.</p>
                    </div>
                    @else
                    <div class="mt-4">
                        <input type="checkbox" wire:model="hasUnderstoodInterviewPreparation" id="understood"
                            class="mr-2">
                        <label for="checkbox" class="text-sm">I understood how to study for interviews.</label>
                    </div>
                    @endif

                </div>

                <div class="mt-6 flex justify-between space-x-4">
                    <a href="{{ route('interview-preparation-study-plan') }}"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow-md">
                        <i class="fa-solid fa-caret-left fa-lg"></i> Revisit Study Plan
                </a>
                    @if (!$hasUnderstoodInterviewPreparation)
                        <button wire:click="cvCreationRequest" wire:model.live="hasUnderstoodInterviewPreparation"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow-md">
                            Complete & Go to CV Creation <i class="fa-solid fa-caret-right fa-lg"></i>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Steps for Interview Preparation Section -->
            <div class="mt-10 bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Steps for Interview Preparation</h3>
                <div>

                    <div class="flex items-center bg-blue-200 rounded-r rounded-full p-1">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-4 text-2xl font-semibold text-white bg-gray-300 border rounded-full shadow-lg">
                            1</div>
                        <div class="flex-grow">
                            <div class="flex items-center">
                                <span
                                    class="font-semibold text-center text-gray-900 mr-4 w-1/3 truncate">Orientation</span>
                                <span class="text-gray-900 w-4/5">Watch videos on how to study and take the test</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-gray-500 pl-32"><i class="fa-solid fa-arrow-down fa-lg"></i></div>

                    <div class="flex items-center bg-blue-200 rounded-r rounded-full p-1">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-4 text-2xl font-semibold text-white bg-gray-300 border rounded-full shadow-lg">
                            2</div>
                        <div class="flex-grow">
                            <div class="flex items-center">
                                <span class="font-semibold text-center text-gray-900 mr-4 w-1/3 truncate">CV
                                    Creation</span>
                                <span class="text-gray-900 w-4/5">Create a CV with an AI trainer</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-gray-500 pl-32"><i class="fa-solid fa-arrow-down fa-lg"></i></div>

                    <div class="flex items-center bg-blue-200 rounded-r rounded-full p-1">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-4 text-2xl font-semibold text-white bg-gray-300 border rounded-full shadow-lg">
                            3</div>
                        <div class="flex-grow">
                            <div class="flex items-center">
                                <span class="font-semibold text-center text-gray-900 mr-4 w-1/3 truncate">Interview
                                    preparation</span>
                                <span class="text-gray-900 w-4/5">Create interview answers with the help of an AI
                                    trainer</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-gray-500 pl-32"><i class="fa-solid fa-arrow-down fa-lg"></i></div>

                    <div class="flex items-center bg-blue-200 rounded-r rounded-full p-1">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-4 text-2xl font-semibold text-white bg-gray-300 border rounded-full shadow-lg">
                            4</div>
                        <div class="flex-grow">
                            <div class="flex items-center">
                                <span class="font-semibold text-center text-gray-900 mr-4 w-1/3 truncate">Interview
                                    Practice</span>
                                <span class="text-gray-900 w-4/5">Practice your interview answers with an AI
                                    trainer</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-gray-500 pl-32"><i class="fa-solid fa-arrow-down fa-lg"></i></div>

                    <div class="flex items-center bg-blue-200 rounded-r rounded-full p-1">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-4 text-2xl font-semibold text-white bg-gray-300 border rounded-full shadow-lg">
                            5</div>
                        <div class="flex-grow">
                            <div class="flex items-center">
                                <span class="font-semibold text-center text-gray-900 mr-4 w-1/3 truncate">Mock
                                    interview</span>
                                <span class="text-gray-900 w-4/5">Take a mock interview with an AI trainer</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-gray-500 pl-32"><i class="fa-solid fa-arrow-down fa-lg"></i></div>

                    <div class="flex items-center bg-blue-200 rounded-r rounded-full p-1">
                        <div
                            class="flex items-center justify-center w-8 h-8 mr-4 text-2xl font-semibold text-white bg-gray-300 border rounded-full shadow-lg">
                            ★</div>
                        <div class="flex-grow">
                            <div class="flex items-center">
                                <span class="font-semibold text-center text-gray-900 mr-4 w-1/3 truncate">Regular
                                    interview</span>
                                <span class="text-gray-900 w-4/5">Meet with your teacher to discuss learning
                                    progress</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('home') }}"
                    class="px-4 py-2 text-gray-700 transition duration-300 bg-gray-200 rounded hover:bg-gray-300">
                    Return to TOP page
                </a>
            </div>
        </div>
    </div>

    <x-popup wire:model="showModal">
        <div class="text-center py-8">
            <h3 class="text-xl font-semibold mb-4">Do you understand how to study for an interview?</h3>

            <div class="flex justify-around py-10">
                <button wire:click="cancelNextStep"
                    class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                    <i class="fa-solid fa-caret-left fa-lg mr-2"></i> Revisit Interview Guideline
                </button>
                <div>
                    <button wire:click="forwardNextStep"
                        class="flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">
                        Understood and Complete <i class="fa-solid fa-caret-right fa-lg ml-2"></i>
                    </button>
                    <p class="flex items-center justify-center text-gray-500 text-sm mt-2">
                        {{ $forwardingStep == 'CV' ? 'Proceed to creating your CV' : 'Go to Interview Practice' }}
                    </p>
                </div>
            </div>
        </div>
    </x-popup>
</div>
