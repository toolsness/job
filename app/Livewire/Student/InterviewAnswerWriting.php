<?php

namespace App\Livewire\Student;

use App\Models\InterviewWritingPractice;
use App\Models\Setting;
use App\Services\InterviewStudyProgressService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use OpenAI;

class InterviewAnswerWriting extends Component
{
    use WithPagination;

    public $elements = [];

    public $studyStartTime;

    public $ai_voice_url;

    public $practiceItem;

    public $userAnswer;

    public $aiGeneratedAnswer;

    public $candidate;

    public $showModal = false;

    public $selectedHistory = null;

    public $isGenerating = false;

    public $selectedForVoicePractice = [];

    public $voicePracticeCollapsed = true;

    public $selectedLanguage = 'english';

    public $supportedLanguages = [
        'english' => 'English',
        'japanese' => '日本語',
        // Add more supported languages here
    ];

    public $questions = [
        'self_introduction' => [
            'english' => 'Can you tell me a bit about yourself?',
            'japanese' => '自己紹介をしていただけますか？',
        ],
        'motivation' => [
            'english' => 'Why are you interested in this position?',
            'japanese' => 'なぜこのポジションに興味がありますか？',
        ],
        'advantages_disadvantages' => [
            'english' => 'What are your strengths, and where do you see room for improvement?',
            'japanese' => 'あなたの長所と、改善が必要だと考える点を教えてください。',
        ],
        'future_plans' => [
            'english' => 'Where do you see yourself in five years?',
            'japanese' => '5年後、あなたはどのようになっていたいですか？',
        ],
        'questions_to_companies' => [
            'english' => 'Do you have any questions for us about the company or the role?',
            'japanese' => '会社や役割について、何か質問はありますか？',
        ],
    ];

    protected $rules = [
        'practiceItem' => 'required',
        'userAnswer' => 'required|min:10',
    ];

    public function mount()
    {
        $this->candidate = Auth::user()->student->candidate;
        $this->loadSelectedForVoicePractice();
    }

    public function toggleVoicePracticeCollapse()
    {
        $this->voicePracticeCollapsed = ! $this->voicePracticeCollapsed;
    }

    public function loadSelectedForVoicePractice()
    {
        $this->selectedForVoicePractice = InterviewWritingPractice::where('candidate_id', $this->candidate->id)
            ->where('selected_for_voice_practice', true)
            ->pluck('id')
            ->toArray();
    }

    public function toggleVoicePracticeSelection($practiceId)
    {
        $practice = InterviewWritingPractice::findOrFail($practiceId);
        $questionType = $practice->question_type;

        if (! $practice->selected_for_voice_practice) {
            // If selecting, unselect all other practices of the same type
            InterviewWritingPractice::where('candidate_id', $this->candidate->id)
                ->where('question_type', $questionType)
                ->where('id', '!=', $practiceId)
                ->update(['selected_for_voice_practice' => false]);

            // Select this practice
            $practice->selected_for_voice_practice = true;
            $practice->save();

            // Ensure only 5 practices are selected for voice practice in total
            $selectedCount = InterviewWritingPractice::where('candidate_id', $this->candidate->id)
                ->where('selected_for_voice_practice', true)
                ->count();

            if ($selectedCount > 5) {
                $oldestSelected = InterviewWritingPractice::where('candidate_id', $this->candidate->id)
                    ->where('selected_for_voice_practice', true)
                    ->where('id', '!=', $practiceId)
                    ->orderBy('updated_at', 'asc')
                    ->first();

                if ($oldestSelected) {
                    $oldestSelected->update(['selected_for_voice_practice' => false]);
                }
            }
        } else {
            // If unselecting, just unselect this practice
            $practice->selected_for_voice_practice = false;
            $practice->save();
        }

        $this->loadSelectedForVoicePractice();

        // Show a flash message
        $action = $practice->selected_for_voice_practice ? 'selected for' : 'unselected from';
        flash()->success("Practice {$action} voice practice successfully.");
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function showHistory($id)
    {
        $this->selectedHistory = InterviewWritingPractice::findOrFail($id);
        $this->showModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showModal = false;
        $this->selectedHistory = null;
    }

    public function updateStudyTime()
    {
        $service = new InterviewStudyProgressService;
        $service->trackStudyTime(10, 'writing');
    }

    public function render()
    {
        $histories = $this->candidate->interviewWritingPractices()
            ->latest()
            ->paginate(3);

        $service = new InterviewStudyProgressService;
        $totalStudyTime = $service->getFormattedStudyTime('total');
        $writingStudyTime = $service->getFormattedStudyTime('writing');

        $voicePracticeOptions = InterviewWritingPractice::where('candidate_id', $this->candidate->id)
            ->where('selected_for_voice_practice', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('question_type');

        return view('livewire.student.interview-answer-writing', [
            'histories' => $histories,
            'totalStudyTime' => $totalStudyTime,
            'writingStudyTime' => $writingStudyTime,
            'voicePracticeOptions' => $voicePracticeOptions,
        ]);
    }

    public function changeLanguage($language)
    {
        $this->selectedLanguage = $language;
    }

    private function getOpenAIResponse($messages, $model = 'gpt-3.5-turbo')
    {
        $aiRegion = Setting::get('ai_region', 'hongkong');

        if ($aiRegion === 'japan') {
            $japanProxyUrl = Setting::get('japan_proxy_url.chat');
            $japanApiKey = Setting::get('japan_api_key');

            $response = Http::withHeaders([
                'X-API-Key' => $japanApiKey,
                'Content-Type' => 'application/json',
            ])->post($japanProxyUrl, [
                'model' => $model,
                'messages' => $messages,
            ]);

            if (! $response->successful()) {
                throw new \Exception('Error from Japan Proxy: '.$response->body());
            }

            return $response->json();
        } else {
            $OpenAiApiKey = getenv('OPENAI_API_KEY');
            $client = OpenAI::client($OpenAiApiKey);

            $response = $client->chat()->create([
                'model' => $model,
                'messages' => $messages,
            ]);

            return $response;
        }
    }

    public function generateAI()
    {
        // Check if practice item is selected before validation
        if (empty($this->practiceItem)) {
            flash()->warning('Please select a practice item first.');

            return;
        }

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (empty($this->userAnswer)) {
                flash()->warning('Please enter your answer before generating AI response.');
            } elseif (strlen($this->userAnswer) < 10) {
                flash()->warning('Your answer must be at least 10 characters long.');
            }

            return;
        }

        $this->isGenerating = true;

        try {
            // Get candidate details
            $candidateDetails = $this->getCandidateDetailsForPrompt();

            // Check if custom prompts are enabled
            $useCustomPrompts = Setting::get('use_custom_prompts.ai_interview_answer_practice', false);

            // Get AI model
            $aiModel = $useCustomPrompts
                ? Setting::get('ai_models.ai_interview_answer_practice', 'gpt-3.5-turbo')
                : 'gpt-3.5-turbo';

            // Get system prompt
            $systemPrompt = $useCustomPrompts
                ? Setting::get('system_prompts.ai_interview_answer_practice', $this->getDefaultSystemPrompt())
                : $this->getDefaultSystemPrompt();

            // Use default user prompt
            $userPrompt = $this->getDefaultUserPrompt();

            // Replace placeholders in prompts
            $systemPrompt = str_replace(
                ['{practiceItem}', '{selectedLanguage}', '{candidateDetails}'],
                [$this->practiceItem, $this->selectedLanguage, $candidateDetails],
                $systemPrompt
            );

            $userPrompt = str_replace(
                ['{question}', '{userAnswer}', '{candidateDetails}'],
                [
                    $this->questions[$this->practiceItem][$this->selectedLanguage],
                    $this->userAnswer,
                    $candidateDetails,
                ],
                $userPrompt
            );

            // Prepare messages
            $messages = [
                [
                    'role' => 'system',
                    'content' => $systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt
                ],
                // Add explicit language instruction
                [
                    'role' => 'system',
                    'content' => $this->selectedLanguage === 'japanese'
                        ? '必ず日本語で回答してください。'
                        : 'Please ensure to respond in English only.'
                ]
            ];

            // Get response using the appropriate region
        $response = $this->getOpenAIResponse($messages, $aiModel);

        // Add debug logging for the AI response
        \Log::info('Raw AI Response:', [
            'response' => $response,
            'selected_language' => $this->selectedLanguage
        ]);

        // Process the response
        if (is_array($response)) {
            $content = $response['choices'][0]['message']['content'];
        } else {
            $content = $response->choices[0]->message->content;
        }

        // Log the content before parsing
        \Log::info('Content before parsing:', ['content' => $content]);

        $parsedContent = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('JSON Parse Error:', [
                'error' => json_last_error_msg(),
                'content' => $content
            ]);
            throw new \Exception('Error parsing AI response: ' . json_last_error_msg());
        }

        // Log the parsed content
        \Log::info('Parsed Content:', ['parsed' => $parsedContent]);

        // Validate the improved_answer exists and is not empty
        if (empty($parsedContent['improved_answer'])) {
            throw new \Exception('AI response does not contain an improved answer');
        }

        $this->aiGeneratedAnswer = $parsedContent['improved_answer'];

        // Log the answer that will be sent to TTS
        \Log::info('Text to be sent to TTS:', ['text' => $this->aiGeneratedAnswer]);

        // Generate TTS only if we have a valid answer
        if (!empty($this->aiGeneratedAnswer)) {
            try {
                $ttsResponse = $this->generateTTSAudio($this->aiGeneratedAnswer, $this->selectedLanguage);

                // Save audio file to S3
                $audioFileName = 'ai_generated_answers/' . uniqid() . '.mp3';

                // Handle the response based on whether it's from Japan proxy or direct OpenAI
                $audioContent = $ttsResponse instanceof \OpenAI\Responses\Audio\Speech\SpeechResponse
                    ? $ttsResponse->getContent()
                    : $ttsResponse;

                if (empty($audioContent)) {
                    throw new \Exception('Empty audio content received from TTS');
                }

                $saved = Storage::disk('s3')->put($audioFileName, $audioContent);

                if (!$saved) {
                    throw new \Exception('Failed to save audio file to S3');
                }

                $this->ai_voice_url = Storage::disk('s3')->url($audioFileName);

                \Log::info('Audio file saved successfully', [
                    'url' => $this->ai_voice_url,
                    'filename' => $audioFileName
                ]);

            } catch (\Exception $e) {
                \Log::error('TTS or Storage Error:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $this->ai_voice_url = null;
            }
        

                // Save to database
                InterviewWritingPractice::create([
                    'candidate_id' => $this->candidate->id,
                    'question_type' => $this->practiceItem,
                    'question' => $this->questions[$this->practiceItem][$this->selectedLanguage],
                    'user_answer' => $this->userAnswer,
                    'improved_answer' => $parsedContent['improved_answer'],
                    'overall_score' => $parsedContent['overall_score'],
                    'content_score' => $parsedContent['content_score'],
                    'language_score' => $parsedContent['language_score'],
                    'structure_score' => $parsedContent['structure_score'],
                    'overall_feedback' => $parsedContent['overall_feedback'],
                    'errors' => json_encode($parsedContent['errors']),
                    'language' => $this->selectedLanguage,
                    'ai_voice_url' => $this->ai_voice_url,
                ]);

                flash()->success('AI-generated answer has been created successfully.');
            } else {
                throw new \Exception('Error parsing AI response');
            }
        } catch (\Exception $e) {
            flash()->error('An error occurred while generating the AI response: '.$e->getMessage());
        }

        $this->isGenerating = false;
    }

    private function generateTTSAudio($text, $language)
{
    $aiRegion = Setting::get('ai_region', 'hongkong');
    $voice = $language === 'japanese' ? 'onyx' : 'nova';

    if ($aiRegion === 'japan') {
        $japanProxyUrl = Setting::get('japan_proxy_url.speech');
        $japanApiKey = Setting::get('japan_api_key');

        \Log::info('Sending TTS request to Japan proxy', [
            'url' => $japanProxyUrl,
            'apiKey' => $japanApiKey,
            'model' => 'tts-1-hd',
            'input' => $text,
            'voice' => $voice,
            'language' => $language,
        ]);

        $response = Http::withHeaders([
            'X-API-Key' => $japanApiKey,
            'Content-Type' => 'application/json',
        ])->post($japanProxyUrl, [
            'model' => 'tts-1-hd',
            'input' => $text,
            'voice' => $voice,
            'response_format' => 'mp3',
            'language' => $language === 'japanese' ? 'ja' : 'en',
        ]);

            \Log::info('Received TTS response from Japan proxy', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if (! $response->successful()) {
                throw new \Exception('Error from Japan Proxy: '.$response->body());
            }

            // Return the audio content directly
            return $response->body();
        } else {
            $OpenAiApiKey = getenv('OPENAI_API_KEY');
            $client = OpenAI::client($OpenAiApiKey);

            return $client->audio()->speech([
                'model' => 'tts-1-hd',
                'input' => $text,
                'voice' => $voice,
                'language' => $language === 'japanese' ? 'ja' : 'en',
            ]);
        }
    }

    private function getDefaultSystemPrompt()
{
    $languageInstruction = $this->selectedLanguage === 'japanese'
        ? '日本語で回答してください。'
        : 'Please respond in English.';

    return "You are an AI interview coach. {$languageInstruction} Your role is to evaluate and improve interview answers while considering the candidate's background and experience.";
}

private function getDefaultUserPrompt()
{
    $languageSpecificInstructions = $this->selectedLanguage === 'japanese'
        ? "以下の面接の回答を評価し、改善してください。JSON形式で回答を提供してください："
        : "Evaluate based on the Candidate's answer below. Give a score based on candidates answer not the improve answer. Then you should think and write the improve the following interview answer. Provide your response in JSON format:";

    return "{$languageSpecificInstructions}
Question to Candidate: {question}
Candidate's Answer: {userAnswer}

Candidate details:
{candidateDetails}

Note Score Generator: score ranges from 0 to 100. Give candidate a score below 80, if you think they need more practice based on candidate's answer. Give scores content score, language score, structure score, and overall score based on candidate's answer and find wrrors from it. overall score ranges from 0 to 100 and its is the mean of content score, language score, and structure score.
Note Improve Answer Generator: A score of 80 is a good score, so try to improve it to 90 or higher when you write the improved answer.
Special Notes:
1. If the candidate's answer is very good, give them a score of 90 or above 90.
2. If the candidate's answer is not very bad, give them a score below 50.
3. If the candidate's answer is good, give them a score of 80 - 90.
4. If the candidate's answer is not good, give them a score below 60.
5. If the candidate's answer is very bad, give them a score below 30.
6. If the candidate's answer is not very good, give them a score of 60 - 70.
7. If the candidate's answer is irrelevant, give them a score of 0.
8. Even If the candidate's answer is irrelevant, You should write the interview improved answer based on question and candidate's answer and candidate's background and experience as much as possible and if in answer candidate's answer is irrelevant, don't write anything in the improved answer based on his answer insted you should write relevant improved interview answer for that candidate based on his details and question.
9. Improved Answer must be in minimum 3 sentences and maximum 5 sentences.
10. Write improved answer as of you are in the interview room and answering the question infront of the Interviewers.
11. If candidate answer is not in the same language as the question, iclude this in the feedback and error. and give a language score of 0.
Provide your response in the following JSON structure:
{
    \"improved_answer\": \"...\",
    \"overall_score\": 0,
    \"content_score\": 0,
    \"language_score\": 0,
    \"structure_score\": 0,
    \"overall_feedback\": \"...\",
    \"errors\": [
        {
            \"type\": \"content|language|structure\",
            \"original\": \"...\",
            \"correction\": \"...\",
            \"explanation\": \"...\"
        }
    ]
}";
}

    private function getCandidateDetailsForPrompt()
    {
        $details = [
            "Name: {$this->candidate->name}",
            "Gender: {$this->candidate->gender}",
            "Age: {$this->candidate->birth_date->age}",
            "Nationality: {$this->candidate->country->country_name}",
            "Last Education: {$this->candidate->last_education}",
            "College: {$this->candidate->college}",
            "Japanese Language Level: {$this->candidate->japanese_language_qualification}",
            "Desired Job Type: {$this->candidate->desiredJobType->name}",
            "Work History: {$this->candidate->work_history}",
        ];

        $qualifications = $this->candidate->qualifications->groupBy('qualificationCategory.name');
        foreach ($qualifications as $category => $quals) {
            $details[] = "{$category}: ".$quals->pluck('qualification_name')->implode(', ');
        }

        return implode("\n", $details);
    }
}
