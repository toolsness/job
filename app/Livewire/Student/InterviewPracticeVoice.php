<?php
// Not using this component Inted used below Controller For This Module
// resources\views\student\interview-practice-voice.blade.php
// app\Http\Controllers\InterviewPracticeController.php
namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\InterviewWritingPractice;
use App\Models\AiVoicePracticeResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenAI;
use Livewire\WithFileUploads;

class InterviewPracticeVoice extends Component
{
    use WithFileUploads;

    public $practice;
    public $currentQuestionIndex = 0;
    public $questions;
    public $savedAnswers;
    public $transcribedAnswer;
    public $currentEvaluation = [];
    public $evaluations = [];
    public $showResults = false;
    public $language = 'english';
    public $isRecording = false;
    public $countdown = 3;
    public $interviewStarted = false;
    public $showStartInterviewPopup = false;
    public $audioFile;
    public $recordingComplete = false;
    public $audioUrl;

    public function mount()
    {
        $this->practice = InterviewWritingPractice::where('candidate_id', Auth::user()->student->candidate->id)
            ->where('selected_for_voice_practice', true)
            ->get();

        $this->questions = $this->practice->pluck('question', 'question_type')->toArray();
        $this->savedAnswers = $this->practice->pluck('improved_answer', 'question_type')->toArray();

        if (empty($this->questions)) {
            flash()->warning('No questions are selected for voice practice. Please select questions first.');
            return redirect()->route('interview-answer.writing');
        }
    }

    public function render()
    {
        return view('livewire.student.interview-practice-voice');
    }

    public function startInterviewCountdown()
    {
        $this->showStartInterviewPopup = true;
    }

    public function startInterview()
    {
        $this->showStartInterviewPopup = false;
        $this->interviewStarted = true;
        $this->currentQuestionIndex = 0;
        $this->askQuestion();
        $this->dispatch('interviewStarted');
    }

    public function askQuestion()
    {
        if (empty($this->questions)) {
            $this->showResults = true;
            return;
        }

        $questionKeys = array_keys($this->questions);
        if ($this->currentQuestionIndex >= count($questionKeys)) {
            $this->showResults = true;
            return;
        }

        $question = $this->questions[$questionKeys[$this->currentQuestionIndex]];
        $this->dispatch('speakQuestion', question: $question, language: $this->language);
    }

    public function processRecording($audioData)
{
    $this->recordingComplete = true;

    // Decode the base64 audio data
    $audioData = base64_decode(preg_replace('#^data:audio/\w+;base64,#i', '', $audioData));

    // Store the audio temporarily
    $tempFilePath = 'temp/' . uniqid() . '.mp3';
    Storage::disk('s3')->put($tempFilePath, $audioData); // Use s3 disk here

    // Transcribe using OpenAI
    $apiKey = env('OPENAI_API_KEY');
    $client = OpenAI::client($apiKey);

    $response = $client->audio()->transcribe([
        'model' => 'whisper-1',
        'file' => fopen(Storage::disk('s3')->path($tempFilePath), 'r'), // Get the full path from the s3 disk
        'response_format' => 'verbose_json',
    ]);

    // Update the component's property with the transcribed text
    $this->updateTranscribedAnswer($response->text);

    // Store audio url in s3
    $this->audioUrl = Storage::disk('s3')->url($tempFilePath);
}

    public function updateTranscribedAnswer($text)
    {
        $this->transcribedAnswer = $text;
        $this->evaluateAnswer();
    }

    private function evaluateAnswer()
    {
        $questionType = array_keys($this->questions)[$this->currentQuestionIndex];
        $savedAnswer = $this->savedAnswers[$questionType];
        $question = $this->questions[$questionType];

        $prompt = "Evaluate the following interview answer for the question: '{$question}'. The candidate's prepared answer was: '{$savedAnswer}'. Their spoken answer was: '{$this->transcribedAnswer}'. Compare the spoken answer to the prepared answer and provide feedback. Respond in " . ($this->language === 'japanese' ? 'Japanese' : 'English') . ". Provide your response in JSON format with the following structure: {\"comparison\": \"...\", \"feedback\": \"...\", \"score\": 0, \"errors\": [{\"type\": \"content|pronunciation|grammar\", \"original\": \"...\", \"correction\": \"...\", \"explanation\": \"...\"}]}";

        $apiKey = env('OPENAI_API_KEY');
        $client = OpenAI::client($apiKey);

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an AI interview coach.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $evaluation = json_decode($response->choices[0]->message->content, true);

        $this->evaluations[] = $evaluation;

        // Save the evaluation to the database
        $practice = $this->practice->where('question_type', $questionType)->first();

        AiVoicePracticeResult::create([
            'interview_writing_practice_id' => $practice->id,
            'user_voice_url' => $this->audioUrl,
            'transcribed_text' => $this->transcribedAnswer,
            'errors' => json_encode($evaluation['errors']),
            'overall_score' => $evaluation['score'],
            'feedback' => $evaluation['feedback'],
        ]);
    }

    public function changeLanguage($language)
    {
        $this->language = $language;
    }
}
