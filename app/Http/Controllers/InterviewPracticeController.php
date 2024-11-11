<?php

namespace App\Http\Controllers;

use App\Models\AiVoicePracticeResult;
use App\Models\InterviewWritingPractice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI;

class InterviewPracticeController extends Controller
{
    public function index()
    {
        $practices = InterviewWritingPractice::where('candidate_id', Auth::user()->student->candidate->id)
            ->where('selected_for_voice_practice', true)
            ->orderBy('question_type', 'asc')
            ->get();

        $practiceData = [];
        foreach ($practices as $practice) {
            $practiceData[$practice->question_type] = [
                'item_name' => ucwords(str_replace('_', ' ', $practice->question_type)),
                'question' => $practice->question,
                'improved_answer' => $practice->improved_answer,
            ];
        }

        // Check if all 5 question types are selected
        if (count($practiceData) < 5) {
            flash()->warning('Please select all 5 question types for voice practice before starting.');

            return redirect()->route('interview-answer.writing');
        }

        return view('student.interview-practice-voice', [
            'practiceData' => $practiceData,
        ]);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Received request for interview practice voice evaluation');

            // Validate the request
            $request->validate([
                'questionType' => 'required|string',
                'audioFile' => 'required|string',
                'language' => 'required|in:english,japanese',
            ]);

            // Get the practice record first
            $practice = InterviewWritingPractice::where('candidate_id', Auth::user()->student->candidate->id)
                ->where('question_type', $request->questionType)
                ->where('selected_for_voice_practice', true)
                ->firstOrFail();

            $questionType = $request->input('questionType');
            $audioData = $request->input('audioFile');
            $language = $request->input('language');

            // Clean the base64 audio data
            $audioData = preg_replace('#^data:audio/\w+;base64,#i', '', $audioData);
            $audioData = str_replace(' ', '+', $audioData);
            $audioData = base64_decode($audioData);

            if (! $audioData) {
                throw new \Exception('Invalid audio data');
            }

            // Create a temporary local file with .mp3 extension
            $localTempFile = tempnam(sys_get_temp_dir(), 'audio_').'.mp3';
            if (! file_put_contents($localTempFile, $audioData)) {
                throw new \Exception('Failed to save audio file');
            }

            // Store the audio on S3
            $s3FilePath = 'interview-recordings/'.uniqid().'.mp3';
            if (! Storage::disk('s3')->put($s3FilePath, file_get_contents($localTempFile))) {
                throw new \Exception('Failed to upload to S3');
            }
            $audioUrl = Storage::disk('s3')->url($s3FilePath);

            Log::info('Audio file uploaded to S3', ['path' => $s3FilePath, 'url' => $audioUrl]);

            // Transcribe using OpenAI Whisper
            $transcribedAnswer = $this->transcribeAudio($localTempFile, $language);

            // Remove the temporary local file
            unlink($localTempFile);

            // Evaluate the answer using the actual question and saved answer from the database
            $evaluation = $this->evaluateAnswer(
                transcribedAnswer: $transcribedAnswer,
                savedAnswer: $practice->improved_answer,
                question: $practice->question,
                language: $language
            );

            // Create AiVoicePracticeResult
            $aiVoicePracticeResult = AiVoicePracticeResult::create([
                'interview_writing_practice_id' => $practice->id,
                'user_voice_url' => $audioUrl,
                'transcribed_text' => $transcribedAnswer,
                'errors' => json_encode($evaluation['errors'] ?? []),
                'overall_score' => $evaluation['overall_score'] ?? 0,
                'content_score' => $evaluation['content_score'] ?? 0,
                'language_score' => $evaluation['language_score'] ?? 0,
                'pronunciation_score' => $evaluation['pronunciation_score'] ?? 0,
                'feedback' => $evaluation['feedback'] ?? '',
                'evaluation' => json_encode([
                    'question' => $practice->question,
                    'saved_answer' => $practice->improved_answer,
                    'comparison' => $evaluation['comparison'] ?? '',
                    'feedback' => $evaluation['feedback'] ?? '',
                    'errors' => $evaluation['errors'] ?? [],
                    'scores' => [
                        'overall' => $evaluation['overall_score'] ?? 0,
                        'content' => $evaluation['content_score'] ?? 0,
                        'language' => $evaluation['language_score'] ?? 0,
                        'pronunciation' => $evaluation['pronunciation_score'] ?? 0,
                    ],
                ]),
            ]);

            return response()->json([
                'success' => true,
                'transcribed_answer' => $transcribedAnswer,
                'evaluation' => $evaluation,
                'user_voice_url' => $audioUrl,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in InterviewPracticeController@store: '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'An error occurred during processing: '.$e->getMessage(),
            ], 500);
        }
    }

    private function transcribeAudio($filePath, $language)
    {
        $aiRegion = Setting::get('ai_region', 'hongkong');

        if ($aiRegion === 'japan') {
            $japanProxyUrl = Setting::get('japan_proxy_url.transcription');
            $japanApiKey = Setting::get('japan_api_key');

            // Convert audio file to base64
            $audioData = base64_encode(file_get_contents($filePath));

            $response = Http::withHeaders([
                'X-API-Key' => $japanApiKey,
                'Content-Type' => 'application/json',
            ])->post($japanProxyUrl, [
                'model' => 'whisper-1',
                'audio' => $audioData,
                'language' => $language === 'japanese' ? 'ja' : 'en',
            ]);

            if (! $response->successful()) {
                throw new \Exception('Error from Japan Proxy: '.$response->body());
            }

            $result = $response->json();

            if (! isset($result['text'])) {
                throw new \Exception('Invalid response format from Japan Proxy');
            }

            return $result['text'];

        } else {
            // Default Singapore/Hong Kong region
            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $response = $client->audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($filePath, 'r'),
                'language' => $language === 'japanese' ? 'ja' : 'en',
            ]);

            return $response->text;
        }
    }

    private function evaluateAnswer($transcribedAnswer, $savedAnswer, $question, $language)
    {
        $aiRegion = Setting::get('ai_region', 'hongkong');

        // Get AI model from settings (only Business Operator can set this)
        $aiModel = Setting::get('ai_models.ai_voice_interview_test', 'gpt-3.5-turbo');

        // Get system prompt from settings (only Business Operator can set this)
        $systemPrompt = Setting::get('system_prompts.ai_voice_interview_test') ?: $this->getDefaultSystemPrompt();

        // Use default user prompt (not customizable by Business Operator)
        $userPrompt = $this->getDefaultUserPrompt();

        // Replace variables in prompts
        $replacements = [
            '{language}' => $language === 'japanese' ? 'Japanese' : 'English',
            '{question}' => $question,
            '{savedAnswer}' => $savedAnswer,
            '{transcribedAnswer}' => $transcribedAnswer,
        ];

        $systemPrompt = str_replace(array_keys($replacements), array_values($replacements), $systemPrompt);
        $userPrompt = str_replace(array_keys($replacements), array_values($replacements), $userPrompt);

        try {
            if ($aiRegion === 'japan') {
                $japanProxyUrl = Setting::get('japan_proxy_url.chat');
                $japanApiKey = Setting::get('japan_api_key');

                $response = Http::withHeaders([
                    'X-API-Key' => $japanApiKey,
                    'Content-Type' => 'application/json',
                ])->post($japanProxyUrl, [
                    'model' => $aiModel,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.3,
                    'response_format' => ['type' => 'json_object'],
                ]);

                if (!$response->successful()) {
                    throw new \Exception('Error from Japan Proxy: ' . $response->body());
                }

                $content = $response->json()['choices'][0]['message']['content'];
            } else {
                // Default Singapore/Hong Kong region
                $client = OpenAI::client(env('OPENAI_API_KEY'));

                $response = $client->chat()->create([
                    'model' => $aiModel,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.3,
                    'response_format' => ['type' => 'json_object'],
                ]);

                $content = $response->choices[0]->message->content;
            }

            // Clean and validate the response
            $content = trim($content);
            $content = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content);

            Log::info('AI Response Content:', ['content' => $content]);

            $evaluation = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            // Validate the evaluation structure and return
            return $this->validateEvaluation($evaluation);

        } catch (\Exception $e) {
            Log::error('Error in evaluateAnswer: ' . $e->getMessage());
            Log::error('System Prompt: ' . $systemPrompt);
            Log::error('User Prompt: ' . $userPrompt);
            throw $e;
        }
    }

    private function getDefaultSystemPrompt()
    {
        return <<<'EOT'
You are an AI interview coach. To Evaluate an answer, you will be given a prepared answer and a spoken answer. The prepared answer will be compared to the spoken answer. The spoken answer will be compared to the prepared answer. Then you will evaluate the answer based on the criteria specified in the instructions.
Instructions:
1. Scores must be integers between 0 and 100
2. Scores must be rounded to the nearest integer
3. The maximum score for each category is 100
4. The minimum score for each category is 0
4. The overall score is the average of the scores for each category
5. Don't give number in the language if prepared answer is not in the same language as the spoken answer.
6. If prepared answer is not in the same language as the spoken answer, iclude this in the feedback and error.
7. for each error, negative -5 marking should be added to the score. for example: if you think he scored 70 in the pronunciation, and you found 3 errors, the pronunciation score should be 70 - 3 * 5 = 55.

Errors Rules:
1. pronunciation, grammar, language, other etc.
2. Error Type, other: Any other type of error that doesn't fit into the above categories, such as using filler words, being too vague, or going off-topic.
3. Error Type, grammar: The type of grammar error, such as missing punctuation or extra punctuation.
4. Error Type, language: The type of language error, if prepared answer is not in the same language as the spoken answer.
5. Error Type, pronunciation: The type of pronunciation error, if prepared answer is not in the same language as the spoken answer.

EOT;
    }

    private function getDefaultUserPrompt()
    {
        return <<<'EOT'
Please evaluate the following interview answer:

Question: {question}

Prepared Answer: {savedAnswer}

Spoken Answer: {transcribedAnswer}

Expected Language: {language}

Requirements:
1. Evaluate the answer based on the criteria specified in the system instructions
2. Provide scores for overall performance, content, language usage, and pronunciation
3. Identify specific errors and provide corrections ( type and explanation should be as per expected language: ({language}), correction should be in the language as prepared answer but orginal text should be in the same language)
4. Compare the spoken answer with the prepared answer
5. Offer constructive feedback for improvement
6. Ensure the response follows the specified JSON format
7. Provide the evaluation in the expected language ({language})
8. Check System Prompt for more instructions. But if here system prompt and user prompt have a diferent instruction for the same rule alwys folow the system prompts for that rule.

Your task is to evaluate interview answers and provide feedback in a strict JSON format. Follow these instructions precisely:

1. Your response MUST be a valid JSON object with EXACTLY these fields, and NO FIELD SHOULD BE EMPTY:
   {
     "comparison": "If no comparison needed, write 'No significant differences to compare.'",
     "feedback": "If no specific feedback, write 'No specific feedback required.'",
     "overall_score": number (0-100),
     "content_score": number (0-100),
     "language_score": number (0-100),
     "pronunciation_score": number (0-100),
     "errors": [
       // If no errors, provide empty array []
       // If errors exist, provide complete error objects
     ]
   }

2. For the "errors" array, each error object MUST have ALL these fields:
   {
     "type": "one of: 'pronunciation', 'grammar', 'language', 'other'",
     "original": "The word or phrase containing the error (as spoken). If no specific text, write 'No specific text'",
     "correction": "The corrected word or phrase. If no correction needed, write 'No correction needed'",
     "explanation": "A clear explanation of the error and why the correction is needed. If no explanation needed, write 'No explanation needed'"
   }

3. IMPORTANT RULES:
   - ALL JSON keys must be in English, regardless of response language
   - ALL fields must have a value (no null or empty strings)
   - Scores must be integers between 0 and 100
   - The "errors" array must always be present (empty array if no errors)
   - Response content can be in {language}, but JSON structure remains in English

4.  Error Type Examples:
    - **pronunciation:** Issues with how a word or sound is pronounced.
        * Example: {"type": "pronunciation", "original": "data STRUCture", "correction": "data structure", "explanation": "The stress should be on the first syllable of 'structure'. "}
    - **grammar:** Mistakes in grammatical rules like verb tenses, articles, or plural forms.
        * Example: {"type": "grammar", "original": "I have went to the store", "correction": "I have gone to the store", "explanation": "Incorrect use of past participle. 'Gone' should be used after 'have'."}
    - **language:** Using words or phrases that are not appropriate for the expected language.
        * Example: {"type": "language", "original": "I am very happy to meet you, amigo!", "correction": "I am very pleased to meet you.", "explanation": "While 'amigo' is friendly, it is not formal English."}
    - **other:** Any other type of error that doesn't fit into the above categories, such as using filler words, being too vague, or going off-topic.
        * Example: {"type": "other", "original": "um... yeah... so...", "correction": "No correction needed", "explanation": "Avoid using filler words like 'um', 'yeah', and 'so'."}

5. Example Response Format:
{
  "comparison": "The spoken answer matches 80% of the prepared content with minor variations in word choice.",
  "feedback": "Good attempt, but needs improvement in pronunciation and grammar.",
  "overall_score": 75,
  "content_score": 80,
  "language_score": 70,
  "pronunciation_score": 70,
  "errors": [
    {
      "type": "grammar",
      "original": "I goes to work",
      "correction": "I go to work",
      "explanation": "Incorrect verb conjugation for first person singular"
    }
  ]
}

6. Special Cases:
   - Wrong Language Used:
     {
       "comparison": "Answer provided in different language than expected",
       "feedback": "Please respond in the required language: {language}",
       "overall_score": 20,
       "content_score": 0,
       "language_score": 0,
       "pronunciation_score": 20,
       "errors": [
         {
           "type": "language",
           "original": "Full response in wrong language",
           "correction": "Please provide answer in {language}",
           "explanation": "Complete response in incorrect language"
         }
       ]
     }

   - No Answer/Silent Response:
     {
       "comparison": "No spoken response provided",
       "feedback": "No answer detected. Please provide a verbal response.",
       "overall_score": 0,
       "content_score": 0,
       "language_score": 0,
       "pronunciation_score": 0,
       "errors": [
         {
           "type": "other",
           "original": "No response",
           "correction": "A verbal response is required",
           "explanation": "No verbal input detected"
         }
       ]
     }

CRITICAL: Ensure ALL fields are present and properly formatted. Never leave any field empty or undefined.

Note: If the spoken answer is in a different language than the prepared answer, please note this in your evaluation and adjust the language score accordingly, and mention that in the feedback.
EOT;
    }

    private function validateEvaluation($evaluation)
    {
        // Define required fields and their types
        $requiredFields = [
            'comparison' => 'string',
            'feedback' => 'string',
            'overall_score' => 'integer',
            'content_score' => 'integer',
            'language_score' => 'integer',
            'pronunciation_score' => 'integer',
            'errors' => 'array',
        ];

        // Validate all required fields and their types
        foreach ($requiredFields as $field => $type) {
            if (! isset($evaluation[$field])) {
                throw new \Exception("Missing required field: $field");
            }

            if ($type === 'integer') {
                $evaluation[$field] = (int) $evaluation[$field];
                if ($evaluation[$field] < 0 || $evaluation[$field] > 100) {
                    throw new \Exception("Score out of range (0-100) for field: $field");
                }
            }

            if ($type === 'string' && empty($evaluation[$field])) {
                $evaluation[$field] = $field === 'comparison' ?
                    'No significant differences to compare.' :
                    'No specific feedback required.';
            }
        }

        // Validate errors array structure
        foreach ($evaluation['errors'] as $index => $error) {
            $requiredErrorFields = ['type', 'original', 'correction', 'explanation'];
            foreach ($requiredErrorFields as $field) {
                if (! isset($error[$field])) {
                    throw new \Exception("Missing required field '$field' in error at index $index");
                }
                if (empty($error[$field])) {
                    $evaluation['errors'][$index][$field] = 'No '.$field.' provided';
                }
            }

            // Validate error type
            $validTypes = ['pronunciation', 'grammar', 'language', 'other'];
            if (! in_array($error['type'], $validTypes)) {
                throw new \Exception("Invalid error type at index $index. Must be one of: ".implode(', ', $validTypes));
            }
        }

        return $evaluation;
    }
}
