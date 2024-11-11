<x-app-layout>
    <x-notification-popup />

    <div class="max-w-3xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header Section -->
            <div class="text-center p-6">
                <h2 class="text-2xl font-bold mb-2">Interview Answer Practice</h2>
                <p class="text-gray-600">The AI trainer will conduct interview practice according to the content
                    registered in the interview answer creation.</p>
            </div>

            <!-- Main Content -->
            <div class="p-6">
                <div class="grid grid-cols-6 gap-4">
                    <!-- Practice Item Selection -->
                    <div class="mb-6 col-span-2">
                        <select id="practiceItem" class="w-full p-3 border border-black rounded-lg text-gray-700 text-sm">
                            <option value="">Please select a practice item</option>
                            @foreach ($practiceData as $key => $data)
                                <option value="{{ $key }}">{{ $data['item_name'] }}</option>
                            @endforeach
                        </select>
                        <div id="practiceItemError" class="mt-1 text-red-500 text-sm hidden">
                            Please select a practice item before starting the recording.
                        </div>
                    </div>
                    <div class="mb-6 col-span-2">
                    </div>
                    <div class="mb-6 col-span-2">
                    </div>
                    <div class="mb-6 col-span-2">
                    </div>

                </div>

                <!-- Interview Content Area -->
                <div id="interviewContent" class="mb-8">
                    <div class="text-center">
                        <div class="border border-black">
                            <div class="flex flex-col items-center">


                                <!-- Interviewer Image -->
                                <div class="w-full">
                                    <div class="relative inline-block">
                                        <img src="{{ asset('interviewer-placeholder.png') }}" alt="Interviewer"
                                            class="w-48 h-48">
                                    </div>
                                </div>

                                <div class="w-full px-6 mt-[-30px] pb-3">
                                    <!-- Question and Answer Section -->
                                    <div class="mt-6 bg-gray-200 p-4 rounded-lg">
                                        <p id="currentQuestion" class="text-gray-800"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recording Controls -->
                        <div class="mt-6 flex justify-center">
                            <button
                                class="start-recording-btn bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full flex items-center space-x-2">
                                <i class="fas fa-microphone"></i>
                                <span>Start Recording</span>
                            </button>
                            <button
                                class="stop-recording-btn hidden bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full flex items-center space-x-2">
                                <i class="fas fa-stop"></i>
                                <span>Stop Recording</span>
                            </button>
                        </div>


                        <!-- Prepared Answer Section -->
                        <div class="mt-8 bg-gray-50 border border-black p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Prepared Answer:</h3>
                            <p id="savedAnswer" class="text-gray-700"></p>
                        </div>


                        <!-- Recording Status -->
                        <div id="recordingStatus" class="mt-6 text-center hidden">
                            <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-full">
                                <div class="recording-indicator mr-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                </div>
                                <span>Recording in progress...</span>
                            </div>
                        </div>

                        <!-- Analyzing Status -->
                        <div id="analyzingStatus" class="mt-6 text-center hidden">
                            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full">
                                <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>Analyzing your answer...</span>
                            </div>
                        </div>

                        <!-- Audio Playback -->
                        <div id="audioPlayback" class="mt-6"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navigation Buttons -->
        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('interview-answer.writing') }}"
                class="border-black text-black border hover:bg-black hover:text-white py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Back to Interview Practice Home
            </a>
            <a href="{{ route('home') }}"
                class="border-black text-black border hover:bg-black hover:text-white py-2 px-4 rounded">
                <i class="fas fa-home mr-2"></i> Return to TOP page
            </a>

            <a href="{{ route('interview-answer.evaluation') }}"
                class="border-black text-black border hover:bg-black hover:text-white py-2 px-4 rounded">
                <i class="fas fa-arrow-right mr-2"></i> All Results
            </a>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .recording-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @keyframes pulse-red {
            0% {
                transform: scale(0.95);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.05);
                opacity: 1;
            }

            100% {
                transform: scale(0.95);
                opacity: 0.5;
            }
        }

        .recording-indicator div {
            animation: pulse-red 1.5s ease infinite;
        }
    </style>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DOM Elements
            const elements = {
                practiceItem: document.getElementById('practiceItem'),
                practiceItemError: document.getElementById('practiceItemError'),
                interview: {
                    content: document.getElementById('interviewContent'),
                    question: {
                        current: document.getElementById('currentQuestion'),
                        savedAnswer: document.getElementById('savedAnswer')
                    },
                    recording: {
                        startBtn: document.querySelector('.start-recording-btn'),
                        stopBtn: document.querySelector('.stop-recording-btn'),
                        status: document.getElementById('recordingStatus'),
                        analyzing: document.getElementById('analyzingStatus'),
                        audioPlayback: document.getElementById('audioPlayback')
                    }
                }
            };

            // Show error message on initial load
            elements.practiceItemError.style.display = 'hidden';

            // Initialize State
            const state = {
                practiceData: @json($practiceData),
                isRecording: false,
                mediaRecorder: null,
                audioChunks: [],
                stream: null
            };

            // Set initial text for question and answer
            elements.interview.question.current.textContent =
                "Please select a Question to start Interview practice";
            elements.interview.question.savedAnswer.textContent = "Select a question to see the prepared answer";
            // Function to detect language
            function detectLanguage(text) {
                const japanesePattern =
                    /[\u3000-\u303F\u3040-\u309F\u30A0-\u30FF\uFF00-\uFFEF\u4E00-\u9FAF\u3400-\u4DBF]/g;
                const matches = text.match(japanesePattern);
                return matches && matches.length >= 5 ? 'japanese' : 'english';
            }

            // Function to speak the question
            function speakQuestion(question, language) {
                speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(question);
                let voices = speechSynthesis.getVoices();

                if (voices.length === 0) {
                    speechSynthesis.onvoiceschanged = () => {
                        voices = speechSynthesis.getVoices();
                        setVoiceAndSpeak();
                    };
                } else {
                    setVoiceAndSpeak();
                }

                function setVoiceAndSpeak() {
                    if (language === 'japanese') {
                        const japaneseVoice = voices.find(voice => voice.lang.includes('ja') || voice.lang.includes(
                            'JP'));
                        if (japaneseVoice) {
                            utterance.voice = japaneseVoice;
                            utterance.lang = 'ja-JP';
                        } else {
                            console.warn('No Japanese voice found');
                        }
                    } else {
                        const englishVoice = voices.find(voice => voice.lang.includes('en-US') || voice.lang
                            .includes('en-GB'));
                        if (englishVoice) {
                            utterance.voice = englishVoice;
                            utterance.lang = 'en-US';
                        }
                    }

                    utterance.rate = 0.9;
                    utterance.pitch = 1;
                    speechSynthesis.speak(utterance);
                }
            }

            // Function to reset the recording UI
            function resetRecordingUI() {
                elements.interview.recording.startBtn.disabled = false;
                elements.interview.recording.startBtn.style.display = 'inline-flex';
                elements.interview.recording.stopBtn.style.display = 'none';
                elements.interview.recording.stopBtn.disabled = true;
                elements.interview.recording.audioPlayback.innerHTML = '';
                elements.interview.recording.status.style.display = 'none';
                elements.interview.recording.analyzing.style.display = 'none';
                state.isRecording = false;
            }

            // Function to handle recording stop
            function handleRecordingStop(questionType, audioBlob) {
                elements.interview.recording.status.style.display = 'none';
                elements.interview.recording.analyzing.style.display = 'block';

                const audioUrl = URL.createObjectURL(audioBlob);
                elements.interview.recording.audioPlayback.innerHTML = `
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Your Recording:</h4>
                        <audio controls src="${audioUrl}" class="w-full"></audio>
                    </div>
                `;

                const reader = new FileReader();
                reader.onloadend = () => {
                    const base64Audio = reader.result;
                    processRecording(base64Audio, questionType);
                };
                reader.readAsDataURL(audioBlob);
            }

            // Function to process the recording
            function processRecording(base64audio, questionType) {
                const practiceItem = state.practiceData[questionType];
                const detectedLanguage = detectLanguage(practiceItem.improved_answer);

                fetch("{{ route('interview-practice.voice.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            questionType: questionType,
                            audioFile: base64audio,
                            language: detectedLanguage
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.error || 'Unknown error occurred');
                        }
                        handleEvaluationResponse(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        elements.interview.recording.analyzing.style.display = 'none';
                        resetRecordingUI();
                    });
            }

            // Function to handle evaluation response
            function handleEvaluationResponse(data) {
                elements.interview.recording.analyzing.style.display = 'none';

                if (data.error) {
                    throw new Error(data.error);
                }

                // Get the current practice item data
                const selectedQuestionType = elements.practiceItem.value;
                const practiceItem = state.practiceData[selectedQuestionType];

                const evaluation = data.evaluation;
                elements.interview.question.savedAnswer.innerHTML = `
        <div class="space-y-6">
            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="ml-3 text-green-700 font-medium">Answer recorded successfully!</p>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h4 class="text-lg font-semibold mb-4">Your Response Analysis</h4>

                <div class="mb-6">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Question:</h5>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded">${practiceItem.question}</p>
                </div>

                <div class="mb-6">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Prepared Answer:</h5>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded">${practiceItem.improved_answer}</p>
                </div>

                <div class="mb-6">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Your Answer (Transcribed):</h5>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded">${data.transcribed_answer}</p>
                </div>

                            <div class="mb-6">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Comparison:</h5>
                                <p class="text-gray-800">${evaluation.comparison}</p>
                            </div>

                            <div class="mb-6">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Feedback:</h5>
                                <p class="text-gray-800">${evaluation.feedback}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h6 class="text-sm font-medium text-gray-700 mb-2">Overall Score</h6>
                                    <p class="text-2xl font-bold">${evaluation.overall_score}/100</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h6 class="text-sm font-medium text-gray-700 mb-2">Content Score</h6>
                                    <p class="text-2xl font-bold">${evaluation.content_score}/100</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h6 class="text-sm font-medium text-gray-700 mb-2">Language Score</h6>
                                    <p class="text-2xl font-bold">${evaluation.language_score}/100</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h6 class="text-sm font-medium text-gray-700 mb-2">Pronunciation Score</h6>
                                    <p class="text-2xl font-bold">${evaluation.pronunciation_score}/100</p>
                                </div>
                            </div>

                            ${evaluation.errors && evaluation.errors.length > 0 ? `
                                                                                <div class="mb-6">
                                                                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Errors Found:</h5>
                                                                                    <div class="overflow-x-auto">
                                                                                        <table class="w-full border-collapse border border-gray-300">
                                                                                            <thead>
                                                                                                <tr class="bg-gray-50">
                                                                                                    <th class="border border-gray-300 p-2 text-left">Type</th>
                                                                                                    <th class="border border-gray-300 p-2 text-left">Original</th>
                                                                                                    <th class="border border-gray-300 p-2 text-left">Correction</th>
                                                                                                    <th class="border border-gray-300 p-2 text-left">Explanation</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                ${evaluation.errors.map(error => `
                                                    <tr>
                                                        <td class="border border-gray-300 p-2">${error.type}</td>
                                                        <td class="border border-gray-300 p-2 text-red-600">${error.original}</td>
                                                        <td class="border border-gray-300 p-2 text-green-600">${error.correction}</td>
                                                        <td class="border border-gray-300 p-2">${error.explanation}</td>
                                                    </tr>
                                                `).join('')}
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            ` : '<p class="text-gray-600">No errors found.</p>'}
                        </div>
                    </div>
                `;
            }

            // Event listener for practice item selection
            elements.practiceItem.addEventListener('change', function() {
                const selectedQuestionType = this.value;

                if (selectedQuestionType) {
                    elements.practiceItemError.style.display = 'none';
                    const practiceItem = state.practiceData[selectedQuestionType];
                    if (practiceItem) {
                        elements.interview.question.current.textContent = practiceItem.question;
                        elements.interview.question.savedAnswer.textContent = practiceItem.improved_answer;

                        // Detect language and speak question
                        const detectedLanguage = detectLanguage(practiceItem.improved_answer);
                        speakQuestion(practiceItem.question, detectedLanguage);

                        // Setup recording for this question
                        setupRecording(selectedQuestionType);
                    }
                } else {
                    elements.practiceItemError.style.display = 'block';
                    elements.interview.question.current.textContent =
                        "Please select a Question to start Interview practice";
                    elements.interview.question.savedAnswer.textContent =
                        "Select a question to see the prepared answer";
                }
            });

            // Setup Recording
            async function setupRecording(questionType) {
                resetRecordingUI();

                const startRecording = async () => {
                    if (!elements.practiceItem.value) {
                        elements.practiceItemError.style.display = 'block';
                        return;
                    }

                    elements.practiceItemError.style.display = 'none';
                    if (state.isRecording) return;

                    try {
                        state.stream = await navigator.mediaDevices.getUserMedia({
                            audio: true
                        });
                        state.mediaRecorder = new MediaRecorder(state.stream);
                        state.audioChunks = [];
                        state.isRecording = true;

                        state.mediaRecorder.ondataavailable = (event) => {
                            if (event.data.size > 0) {
                                state.audioChunks.push(event.data);
                            }
                        };

                        state.mediaRecorder.onstop = () => {
                            const audioBlob = new Blob(state.audioChunks, {
                                type: 'audio/wav'
                            });
                            handleRecordingStop(questionType, audioBlob);
                        };

                        state.mediaRecorder.start();

                        elements.interview.recording.startBtn.style.display = 'none';
                        elements.interview.recording.stopBtn.style.display = 'inline-flex';
                        elements.interview.recording.status.style.display = 'block';

                        elements.interview.recording.stopBtn.disabled = true;
                        setTimeout(() => {
                            elements.interview.recording.stopBtn.disabled = false;
                        }, 3000);

                    } catch (err) {
                        console.error('Error accessing microphone:', err);
                        resetRecordingUI();
                    }
                };

                // Add click event listener to start recording button
                elements.interview.recording.startBtn.addEventListener('click', () => {
                    if (!elements.practiceItem.value) {
                        elements.practiceItemError.style.display = 'block';
                        return;
                    }
                    startRecording();
                });

                const stopRecording = () => {
                    if (!state.isRecording) return;

                    if (state.mediaRecorder?.state === 'recording') {
                        state.mediaRecorder.stop();
                        state.stream.getTracks().forEach(track => track.stop());
                    }

                    elements.interview.recording.status.style.display = 'none';
                    elements.interview.recording.stopBtn.style.display = 'none';
                    elements.interview.recording.analyzing.style.display = 'block';
                };

                elements.interview.recording.startBtn.addEventListener('click', startRecording);
                elements.interview.recording.stopBtn.addEventListener('click', stopRecording);
            }

            // Check microphone permission on page load
            async function checkMicrophonePermission() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });
                    stream.getTracks().forEach(track => track.stop());
                    return true;
                } catch (err) {
                    console.error('Microphone permission error:', err);
                    return false;
                }
            }

            // Check microphone permission on page load
            checkMicrophonePermission().then(hasPermission => {
                if (!hasPermission) {
                    alert('Please allow microphone access to use the voice practice feature.');
                }
            });
        });

        // Function to show flash warning
        // Function to show flash warning
        function showFlashWarning() {
            window.flasher.warning('Please select a practice item before starting the recording.');
        }
    </script>
</x-app-layout>
