<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Interview Practice - Voice Evaluation</h2>

    @if (!$interviewStarted)
        <div class="text-center mb-6">
            <img src="{{ asset('interviewer-placeholder.png') }}" alt="Interviewer" class="mx-auto w-1/4">
            <p class="mt-4 text-lg font-medium">Welcome! Let's prepare for your interview.</p>
        </div>

        <div class="mb-4">
            <label for="language" class="block text-sm font-medium text-gray-700">Select Language:</label>
            <select id="language" wire:model="language" wire:change="changeLanguage($event.target.value)"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="english">English</option>
                <option value="japanese">Japanese</option>
            </select>
        </div>

        <button wire:click="startInterviewCountdown"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Start Interview</button>

        <x-popup wire:model="showStartInterviewPopup">
            <div class="text-center">
                <h3 class="text-lg font-medium mb-4">Preparing Your Interview</h3>
                <div x-data="{ countdown: 3 }" x-init="$watch('$wire.showStartInterviewPopup', value => {
                    if (value) {
                        let timer = setInterval(() => {
                            if (countdown > 0) countdown--;
                            if (countdown === 0) {
                                clearInterval(timer);
                                $wire.startInterview();
                            }
                        }, 1000);
                    }
                })">
                    <p class="text-3xl font-bold mb-4" x-text="countdown === 0 ? 'Starting...' : countdown"></p>
                    <p>Get ready for your interview!</p>
                </div>
            </div>
        </x-popup>
    @endif

    @if ($interviewStarted && !$showResults)
    <div class="mb-4">
        <h3 class="text-lg font-semibold">{{ $questions[array_keys($questions)[$currentQuestionIndex]] }}</h3>
        <p class="text-gray-600">Saved Answer: {{ $savedAnswers[array_keys($questions)[$currentQuestionIndex]] }}</p>
    </div>

    <div id="recordingControls" class="mb-4">
        <button id="startRecording" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @if($recordingComplete) disabled @endif>Start Recording</button>
        <button id="stopRecording" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" disabled>Stop Recording</button>
    </div>

    <div id="audioPlayback" class="mt-4"></div>
    <audio id="audioCapture"></audio>

    @if ($transcribedAnswer)
        <div class="mt-4">
            <h4 class="text-lg font-semibold">Transcribed Answer:</h4>
            <p>{{ $transcribedAnswer }}</p>
        </div>
    @endif

    @if (!empty($currentEvaluation))
        <div class="mt-4 p-4 border border-gray-300 rounded">
            <h4 class="font-semibold">Evaluation:</h4>
            <p><strong>Comparison:</strong> {{ $currentEvaluation['comparison'] }}</p>
            <p><strong>Feedback:</strong> {{ $currentEvaluation['feedback'] }}</p>
            <p><strong>Score:</strong> {{ $currentEvaluation['score'] }}/100</p>
        </div>
        @if($currentQuestionIndex < count($questions)-1)
            <button wire:click="askQuestion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Next Question</button>
        @else
            <button wire:click="showResults=true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Show Results</button>
        @endif
    @endif
@endif

    @if ($showResults)
        <h3 class="text-xl font-bold mb-4">Practice Results</h3>
        @foreach ($evaluations as $index => $evaluation)
            <div class="mb-4 p-4 border border-gray-300 rounded">
                <h4 class="font-semibold">Question {{ $index + 1 }}: {{ $questions[array_keys($questions)[$index]] }}
                </h4>
                <p><strong>Your Answer:</strong> {{ $practice[$index]->user_answer }}</p>
                <p><strong>Comparison:</strong> {{ $evaluation['comparison'] }}</p>
                <p><strong>Feedback:</strong> {{ $evaluation['feedback'] }}</p>
                <p><strong>Score:</strong> {{ $evaluation['score'] }}/100</p>
            </div>
        @endforeach
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startRecordingButton = document.getElementById('startRecording');
            const stopRecordingButton = document.getElementById('stopRecording');
            const audioPlayback = document.getElementById('audioPlayback');

            let mediaRecorder;
            let audioChunks = [];
            let stream;

            async function startRecording() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({audio: true});
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.start();

                    startRecordingButton.disabled = true;
                    stopRecordingButton.disabled = false;

                    mediaRecorder.ondataavailable = event => {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(audioChunks, {type: 'audio/mpeg'});
                        audioChunks = [];

                        const reader = new FileReader();
                        reader.onloadend = () => {
                            const base64audio = reader.result;
                            @this.call('processRecording', base64audio); // Pass base64 audio to Livewire
                        };
                        reader.readAsDataURL(audioBlob); // Read as data URL for base64 encoding

                        const audioUrl = URL.createObjectURL(audioBlob);
                        audioPlayback.innerHTML = `<audio controls src="${audioUrl}"></audio>`;

                    };
                } catch (err) {
                    console.error('Error accessing microphone:', err);
                    alert('Error accessing microphone. Please ensure you have granted permission.');
                }
            }

            function stopRecording() {
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                    stream.getTracks().forEach(track => track.stop());
                }
                startRecordingButton.disabled = false;
                stopRecordingButton.disabled = true;
            }

            startRecordingButton.addEventListener('click', startRecording);
            stopRecordingButton.addEventListener('click', stopRecording);

            @this.on('speakQuestion', (event) => {
                const question = event.detail.question;
                const language = event.detail.language;
                const utterance = new SpeechSynthesisUtterance(question);
                utterance.lang = language === 'japanese' ? 'ja-JP' : 'en-US';
                speechSynthesis.speak(utterance);
            });

            Livewire.on('interviewStarted', () => {
                startRecordingButton.disabled = false;
            });
        });
    </script>
</div>
