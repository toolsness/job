<?php

namespace App\Livewire\Student;

use App\Models\Vacancy;
use App\Models\VacancyCategory;
use App\Services\InterviewStudyProgressService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use OpenAI;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class InterviewAnswerPractice extends Component
{
    use WithPagination;

    public $showStartInterviewPopup = false;

    public $isInterviewStarting = false;

    public $studyStartTime;

    public $candidate;

    public $vacancySelectionMethod = '';

    public $selectedVacancy = null;

    public $selectedVacancyCategory = null;

    public $generatedJobTitles = [];

    public $selectedJobTitle = '';

    public $currentQuestionIndex = -1;

    public $aiQuestion = '';

    public $answer = '';

    public $evaluation = null;

    public $isProcessing = false;

    public $showResults = false;

    public $practiceResults = [];

    public $questions = [
        'self_introduction' => 'Can you tell me a bit about yourself?',
        'motivation' => 'Why are you interested in this position?',
        'advantages_disadvantages' => 'What are your strengths, and where do you see room for improvement?',
        'future_plans' => 'Where do you see yourself in five years?',
        'questions_to_companies' => 'Do you have any questions for us about the company or the role?',
    ];

    protected $rules = [
        'vacancySelectionMethod' => 'required|in:existing,generate',
        'selectedVacancy' => 'required_if:vacancySelectionMethod,existing',
        'selectedVacancyCategory' => 'required_if:vacancySelectionMethod,generate',
        'selectedJobTitle' => 'required_if:vacancySelectionMethod,generate',
        'answer' => 'required|min:10',
    ];

    public function mount()
    {
        $this->candidate = Auth::user()->student->candidate;
        $this->studyStartTime = now();
    }

    public function render()
    {
        $vacancies = Vacancy::where('publish_category', 'Published')->get();
        $vacancyCategories = VacancyCategory::all();

        $service = new InterviewStudyProgressService;
        $totalStudyTime = $service->getFormattedStudyTime('total');
        $practiceStudyTime = $service->getFormattedStudyTime('practice');

        return view('livewire.student.interview-answer-practice', [
            'vacancies' => $vacancies,
            'vacancyCategories' => $vacancyCategories,
            'totalStudyTime' => $totalStudyTime,
            'practiceStudyTime' => $practiceStudyTime,
        ]);
    }

    public function updatedVacancySelectionMethod()
    {
        $this->reset(['selectedVacancy', 'selectedVacancyCategory', 'generatedJobTitles', 'selectedJobTitle']);
    }

    public function updatedSelectedVacancyCategory($value)
    {
        if ($value) {
            $this->generateJobTitles();
        } else {
            $this->generatedJobTitles = [];
        }
    }

    public function generateJobTitles()
    {
        $this->isProcessing = true;
        $category = VacancyCategory::find($this->selectedVacancyCategory);
        if (! $category) {
            $this->isProcessing = false;
            flash()->error('Invalid category selected. Please try again.');

            return;
        }

        $prompt = "Generate 10 job titles for the {$category->name} industry, ranging from entry-level to senior positions. Format the response as a JSON array of strings. Example output: [\"Junior Software Developer\", \"Senior Project Manager\", \"Data Analyst\", \"Chief Technology Officer\"]";

        $aiRegion = Setting::get('ai_region', 'hongkong');

        if ($aiRegion === 'japan') {
            $japanProxyUrl = Setting::get('japan_proxy_url.chat');
            $japanApiKey = Setting::get('japan_api_key');

            $response = Http::withHeaders([
                'X-API-Key' => $japanApiKey,
                'Content-Type' => 'application/json',
            ])->post($japanProxyUrl, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that generates job titles.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if (! $response->successful()) {
                $this->isProcessing = false;
                flash()->error('Failed to generate job titles. Please try again.');
                \Log::error('Job title generation error: '.$response->body());
                return;
            }

            $content = $response->json()['choices'][0]['message']['content'];
        } else {
            $OpenAiApiKey = getenv('OPENAI_API_KEY');
            $client = OpenAI::client($OpenAiApiKey);

            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that generates job titles.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $content = $response->choices[0]->message->content;
        }

        $this->generatedJobTitles = json_decode($content, true);

        if (! is_array($this->generatedJobTitles) || empty($this->generatedJobTitles)) {
            $this->generatedJobTitles = [];
            flash()->error('Failed to generate job titles. Please try again.');
            \Log::error('Job title generation error: Invalid response format');
        } else {
            flash()->success('Job titles generated successfully.');
        }

        $this->isProcessing = false;
    }

    public function startInterviewCountdown()
    {
        $this->validate([
            'vacancySelectionMethod' => 'required',
            'selectedVacancy' => 'required_if:vacancySelectionMethod,existing',
            'selectedJobTitle' => 'required_if:vacancySelectionMethod,generate',
        ]);

        $this->showStartInterviewPopup = true;
    }

    public function startInterview()
    {
        $this->showStartInterviewPopup = false;
        $this->isInterviewStarting = false;
        $this->currentQuestionIndex = 0;
        $this->practiceResults = [];
        $this->showResults = false;
        $this->nextQuestion();
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions)) {
            $this->aiQuestion = $this->questions[array_keys($this->questions)[$this->currentQuestionIndex]];
            $this->answer = '';
            $this->evaluation = null;
        } else {
            $this->showResults = true;
        }
    }

    public function evaluate()
    {
        $this->validate(['answer' => 'required|min:10']);

        try {
            $aiRegion = Setting::get('ai_region', 'hongkong');
            $jobTitle = $this->selectedJobTitle ?: optional($this->selectedVacancy)->job_title ?? 'the position';
            $questionType = array_keys($this->questions)[$this->currentQuestionIndex];
            $example = $this->getAIPromptExample($questionType);

            if ($aiRegion === 'japan') {
                $japanProxyUrl = Setting::get('japan_proxy_url.chat');
                $japanApiKey = Setting::get('japan_api_key');

                $response = Http::withHeaders([
                    'X-API-Key' => $japanApiKey,
                    'Content-Type' => 'application/json',
                ])->post($japanProxyUrl, [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => "You are an AI interview evaluator for a {$jobTitle} position. Evaluate the following answer for the interview question. Provide an improved answer, focusing on enhancing the user's actual response. Identify specific errors in grammar, content, and structure. Pay special attention to grammatical mistakes and provide corrections for each. Respond in the same language as the user's answer. Provide your response in JSON format with the following structure: {\"improved_answer\": \"...\", \"overall_score\": 0, \"content_score\": 0, \"language_score\": 0, \"structure_score\": 0, \"overall_feedback\": \"...\", \"errors\": [{\"type\": \"grammar|content|structure\", \"original\": \"...\", \"correction\": \"...\", \"explanation\": \"...\"}]}. Limit the improved answer to 200 words and provide up to 5 specific errors. Here are examples in English and Japanese:\n\nEnglish example:\nQuestion: {$example['en']['question']}\nAnswer: {$example['en']['answer']}\nEvaluation: {$example['en']['evaluation']}\n\nJapanese example:\nQuestion: {$example['ja']['question']}\nAnswer: {$example['ja']['answer']}\nEvaluation: {$example['ja']['evaluation']}"],
                        ['role' => 'user', 'content' => "Question: {$this->aiQuestion}\nAnswer: {$this->answer}"],
                    ],
                ]);

                if (! $response->successful()) {
                    throw new \Exception('Error from Japan Proxy: '.$response->body());
                }

                $content = $response->json()['choices'][0]['message']['content'];
            } else {
                $OpenAiApiKey = getenv('OPENAI_API_KEY');
                $client = OpenAI::client($OpenAiApiKey);

                $response = $client->chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => "You are an AI interview evaluator for a {$jobTitle} position. Evaluate the following answer for the interview question. Provide an improved answer, focusing on enhancing the user's actual response. Identify specific errors in grammar, content, and structure. Pay special attention to grammatical mistakes and provide corrections for each. Respond in the same language as the user's answer. Provide your response in JSON format with the following structure: {\"improved_answer\": \"...\", \"overall_score\": 0, \"content_score\": 0, \"language_score\": 0, \"structure_score\": 0, \"overall_feedback\": \"...\", \"errors\": [{\"type\": \"grammar|content|structure\", \"original\": \"...\", \"correction\": \"...\", \"explanation\": \"...\"}]}. Limit the improved answer to 200 words and provide up to 5 specific errors. Here are examples in English and Japanese:\n\nEnglish example:\nQuestion: {$example['en']['question']}\nAnswer: {$example['en']['answer']}\nEvaluation: {$example['en']['evaluation']}\n\nJapanese example:\nQuestion: {$example['ja']['question']}\nAnswer: {$example['ja']['answer']}\nEvaluation: {$example['ja']['evaluation']}"],
                        ['role' => 'user', 'content' => "Question: {$this->aiQuestion}\nAnswer: {$this->answer}"],
                    ],
                ]);

                $content = $response->choices[0]->message->content;
            }

            $evaluation = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to parse AI response');
            }

            $this->evaluation = $evaluation;
            $this->practiceResults[] = [
                'question' => $this->aiQuestion,
                'answer' => $this->answer,
                'evaluation' => $this->evaluation,
            ];

            // Save practice and evaluation
            $practice = $this->candidate->interviewPractices()->create([
                'total_score' => $evaluation['overall_score'],
                'summary_feedback' => $evaluation['overall_feedback'],
            ]);

            $practice->options()->create([
                'option_type' => $questionType,
                'question' => $this->aiQuestion,
                'user_text' => $this->answer,
                'improved_answer' => $evaluation['improved_answer'],
                'overall_score' => $evaluation['overall_score'],
                'content_score' => $evaluation['content_score'],
                'language_score' => $evaluation['language_score'],
                'structure_score' => $evaluation['structure_score'],
                'overall_feedback' => $evaluation['overall_feedback'],
                'errors' => json_encode($evaluation['errors']),
                'practice_mode' => 'text',
            ]);

            $this->currentQuestionIndex++;
            $this->nextQuestion();

            flash()->success('Answer evaluated successfully.');
        } catch (\Exception $e) {
            flash()->error('Failed to evaluate answer. Please try again.');
            \Log::error('Answer evaluation error: '.$e->getMessage());
        }
    }

    public function showPracticeResults()
    {
        $this->showResults = true;
    }

    private function getAIPromptExample($questionType)
    {
        $examples = [
            'self_introduction' => [
                'en' => [
                    'question' => 'Can you tell me a bit about yourself?',
                    'answer' => "Hi, I'm John. I graduated university last years. I like coding and did an internship. I am good in teamwork.",
                    'evaluation' => json_encode([
                        'improved_answer' => "Hello, I'm John Doe, a recent Computer Science graduate from XYZ University. I'm passionate about software development, particularly web technologies. During my studies, I completed an internship at ABC Tech, where I contributed to developing a customer management system. I excel at teamwork and am excited to bring my skills to a collaborative environment.",
                        'overall_score' => 70,
                        'content_score' => 75,
                        'language_score' => 65,
                        'structure_score' => 70,
                        'overall_feedback' => 'Your introduction covers basic points but lacks specific details. Try to provide more information about your education, skills, and experiences. Also, pay attention to grammar and use more professional language.',
                        'errors' => [
                            [
                                'type' => 'grammar',
                                'original' => 'I graduated university last years',
                                'correction' => 'I graduated from university last year',
                                'explanation' => "Use 'graduated from' and the singular 'year' for past events",
                            ],
                            [
                                'type' => 'content',
                                'original' => 'I like coding',
                                'correction' => "I'm passionate about software development",
                                'explanation' => 'Use more professional language and be more specific about your interests',
                            ],
                            [
                                'type' => 'structure',
                                'original' => 'I did an internship',
                                'correction' => 'During my studies, I completed an internship at [Company], where I [specific accomplishment]',
                                'explanation' => 'Provide more context and details about your experiences',
                            ],
                            [
                                'type' => 'grammar',
                                'original' => 'I am good in teamwork',
                                'correction' => 'I excel at teamwork',
                                'explanation' => "Use 'excel at' instead of 'good in' for a more professional tone",
                            ],
                        ],
                    ]),
                ],
                'ja' => [
                    'question' => '自己紹介をお願いします。',
                    'answer' => 'はじめまして。私は田中です。大学を卒業しました去年。プログラミングが好きで、インターンシップをしました。チームワークが得意だ。',
                    'evaluation' => json_encode([
                        'improved_answer' => 'はじめまして。私は田中一郎と申します。昨年、XYZ大学のコンピューターサイエンス学部を卒業しました。ソフトウェア開発、特にWeb技術に情熱を持っています。学生時代には、ABC株式会社でインターンシップを経験し、顧客管理システムの開発に携わりました。チームワークを大切にし、協調的な環境で自分のスキルを活かすことを楽しみにしています。',
                        'overall_score' => 70,
                        'content_score' => 75,
                        'language_score' => 65,
                        'structure_score' => 70,
                        'overall_feedback' => '基本的な情報は含まれていますが、具体的な詳細が不足しています。教育、スキル、経験についてより多くの情報を提供するよう心がけてください。また、文法に注意し、よりプロフェッショナルな言葉遣いを使用してください。',
                        'errors' => [
                            [
                                'type' => 'grammar',
                                'original' => '大学を卒業しました去年',
                                'correction' => '昨年、大学を卒業しました',
                                'explanation' => '時間を表す言葉は文の始めか終わりに置くのが自然です',
                            ],
                            [
                                'type' => 'content',
                                'original' => 'プログラミングが好きです',
                                'correction' => 'ソフトウェア開発、特にWeb技術に情熱を持っています',
                                'explanation' => 'より具体的かつ専門的な表現を使用してください',
                            ],
                            [
                                'type' => 'structure',
                                'original' => 'インターンシップをしました',
                                'correction' => '学生時代には、[会社名]でインターンシップを経験し、[具体的な成果]に携わりました',
                                'explanation' => '経験についてより詳細な情報を提供してください',
                            ],
                            [
                                'type' => 'grammar',
                                'original' => 'チームワークが得意だ',
                                'correction' => 'チームワークを大切にしています',
                                'explanation' => 'より丁寧で専門的な表現を使用してください',
                            ],
                        ],
                    ]),
                ],
            ],
            'motivation' => [
                'en' => [
                    'question' => 'Why are you interested in this position?',
                    'answer' => "I want to work here because it's a good company. I like the job and think I can do it well. I want to learn more things.",
                    'evaluation' => json_encode([
                        'improved_answer' => "I'm particularly interested in this position because it aligns perfectly with my career goals in software development. Your company's innovative approach to AI-driven solutions, especially the recent launch of your machine learning platform, really impresses me. I've been following your company's growth and contributions to the tech industry, and I'm eager to be part of such a forward-thinking team. My experience in developing scalable web applications and my recent projects in AI make me confident that I can contribute effectively to your team's success while also expanding my skills in this rapidly evolving field.",
                        'overall_score' => 65,
                        'content_score' => 60,
                        'language_score' => 70,
                        'structure_score' => 65,
                        'overall_feedback' => "Your answer shows basic interest but lacks specific details about the company and the position. Try to demonstrate your knowledge about the company and explain how your skills and experiences align with the role. Use more professional language and provide concrete examples of why you're a good fit.",
                        'errors' => [
                            [
                                'type' => 'content',
                                'original' => "it's a good company",
                                'correction' => "Your company's innovative approach to AI-driven solutions really impresses me",
                                'explanation' => "Provide specific reasons why you're interested in the company",
                            ],
                            [
                                'type' => 'grammar',
                                'original' => 'I like the job and think I can do it well',
                                'correction' => "I'm confident that my skills in [specific areas] make me well-suited for this role",
                                'explanation' => 'Use more professional language and be specific about your skills',
                            ],
                            [
                                'type' => 'structure',
                                'original' => 'I want to learn more things',
                                'correction' => "I'm eager to expand my skills in [specific areas] while contributing to your team's success",
                                'explanation' => 'Be more specific about what you want to learn and how it relates to the job',
                            ],
                        ],
                    ]),
                ],
                'ja' => [
                    'question' => 'なぜこのポジションに興味がありますか？',
                    'answer' => 'いい会社だから、ここで働きたいです。仕事が好きで、うまくできると思います。もっと多くのことを学びたいです。',
                    'evaluation' => json_encode([
                        'improved_answer' => 'このポジションに特に興味を持っているのは、私のキャリア目標とソフトウェア開発の分野で完全に一致しているからです。特に、最近立ち上げられた機械学習プラットフォームなど、AIドリブンのソリューションに対する御社の革新的なアプローチに感銘を受けました。御社の成長とテクノロジー業界への貢献を注目してきました。私のスケーラブルなWebアプリケーション開発の経験と最近のAIプロジェクトでの経験を活かし、チームの成功に効果的に貢献できると確信しています。同時に、この急速に進化する分野で自身のスキルを拡張できることを楽しみにしています。',
                        'overall_score' => 65,
                        'content_score' => 60,
                        'language_score' => 70,
                        'structure_score' => 65,
                        'overall_feedback' => '基本的な興味は示されていますが、会社とポジションに関する具体的な詳細が不足しています。会社に関する知識を示し、あなたのスキルと経験がどのように役割に適しているかを説明するよう心がけてください。より専門的な言葉遣いを使用し、なぜあなたがこの職位に適しているかの具体的な例を提供してください。',
                        'errors' => [
                            [
                                'type' => 'content',
                                'original' => 'いい会社だから',
                                'correction' => 'AIドリブンのソリューションに対する御社の革新的なアプローチに感銘を受けました',
                                'explanation' => '会社に興味がある具体的な理由を提供してください',
                            ],
                            [
                                'type' => 'grammar',
                                'original' => '仕事が好きで、うまくできると思います',
                                'correction' => '私の[特定の分野]のスキルを活かし、この役割に貢献できると確信しています',
                                'explanation' => 'より専門的な言葉遣いを使用し、スキルについて具体的に述べてください',
                            ],
                            [
                                'type' => 'structure',
                                'original' => 'もっと多くのことを学びたいです',
                                'correction' => 'チームの成功に貢献しながら、[特定の分野]のスキルを拡張できることを楽しみにしています',
                                'explanation' => '学びたいことをより具体的に述べ、それが仕事にどのように関連するかを説明してください',
                            ],
                        ],
                    ]),
                ],
            ],
            'advantages_disadvantages' => [
                'en' => [
                    'question' => 'What are your strengths, and where do you see room for improvement?',
                    'answer' => "I'm good at coding and solving problems. I work hard. My weakness is I'm not good at public speaking.",
                    'evaluation' => json_encode([
                        'improved_answer' => "One of my key strengths is my proficiency in coding, particularly in languages like Python and JavaScript. I have a strong problem-solving ability, which I demonstrated in my last project where I optimized a database query, reducing processing time by 40%. I'm also known for my strong work ethic and ability to meet deadlines consistently. However, I recognize that I have room for improvement in public speaking. I sometimes feel nervous presenting to large groups, but I'm actively working on this by taking a public speaking course and volunteering to lead more team presentations.",
                        'overall_score' => 70,
                        'content_score' => 75,
                        'language_score' => 70,
                        'structure_score' => 65,
                        'overall_feedback' => "Your answer touches on both strengths and areas for improvement, which is good. However, try to provide more specific examples and details. For strengths, mention particular skills and quantify your achievements if possible. For areas of improvement, it's great that you've identified one, but also mention how you're working to address it.",
                        'errors' => [
                            [
                                'type' => 'content',
                                'original' => "I'm good at coding",
                                'correction' => "I'm proficient in coding, particularly in languages like Python and JavaScript",
                                'explanation' => 'Be more specific about your coding skills',
                            ],
                            [
                                'type' => 'structure',
                                'original' => 'I work hard',
                                'correction' => 'I have a strong work ethic and consistently meet deadlines',
                                'explanation' => "Elaborate on what 'working hard' means in practical terms",
                            ],
                            [
                                'type' => 'content',
                                'original' => "My weakness is I'm not good at public speaking",
                                'correction' => "An area I'm improving is public speaking. I'm taking a course and volunteering for more presentation opportunities to address this",
                                'explanation' => "Mention how you're actively working on improving your weakness",
                            ],
                        ],
                    ]),
                ],
                'ja' => [
                    'question' => 'あなたの強みは何ですか？また、改善の余地はどこにあると考えていますか？',
                    'answer' => 'コーディングと問題解決が得意です。一生懸命働きます。弱点はプレゼンテーションが苦手なことです。',
                    'evaluation' => json_encode([
                        'improved_answer' => '私の主な強みの一つは、PythonやJavaScriptなどの言語を使ったコーディングの熟練度です。問題解決能力も高く、前回のプロジェクトではデータベースクエリを最適化し、処理時間を40%削減しました。また、強い仕事への倫理観を持ち、常に締め切りを守ることでも知られています。一方で、プレゼンテーションスキルには改善の余地があると認識しています。大勢の前で話すときに緊張することがありますが、現在、プレゼンテーション講座を受講し、チームでのプレゼンテーションをリードする機会を積極的に求めるなど、この課題に取り組んでいます。',
                        'overall_score' => 70,
                        'content_score' => 75,
                        'language_score' => 70,
                        'structure_score' => 65,
                        'overall_feedback' => '強みと改善点の両方に触れているのは良いですが、もっと具体的な例や詳細を提供するよう心がけてください。強みについては、特定のスキルを挙げ、可能であれば成果を数値化してください。改善点については、一つ挙げられているのは良いですが、それをどのように改善しようとしているかも言及するとよいでしょう。',
                        'errors' => [
                            [
                                'type' => 'content',
                                'original' => 'コーディングが得意です',
                                'correction' => 'PythonやJavaScriptなどの言語を使ったコーディングに熟練しています',
                                'explanation' => 'コーディングスキルについてより具体的に述べてください',
                            ],
                            [
                                'type' => 'structure',
                                'original' => '一生懸命働きます',
                                'correction' => '強い仕事への倫理観を持ち、常に締め切りを守ることができます',
                                'explanation' => '「一生懸命働く」ことが実際にどのような意味を持つか、具体的に説明してください',
                            ],
                            [
                                'type' => 'content',
                                'original' => '弱点はプレゼンテーションが苦手なことです',
                                'correction' => 'プレゼンテーションスキルは改善中の分野です。講座を受講し、より多くのプレゼンテーションの機会を求めてこの課題に取り組んでいます',
                                'explanation' => '弱点を改善するために積極的に取り組んでいることを言及してください',
                            ],
                        ],
                    ]),
                ],
            ],
            'future_plans' => [
                'en' => [
                    'question' => 'Where do you see yourself in five years?',
                    'answer' => 'I want to have a good job and maybe be a manager. I hope to learn new things and make more money.',
                    'evaluation' => json_encode([
                        'improved_answer' => "In five years, I envision myself as a senior software developer, potentially leading a team in innovative AI projects. I plan to deepen my expertise in machine learning and data analytics, possibly pursuing a master's degree part-time to support this goal. I'm also keen on contributing to open-source projects in the AI field to stay at the forefront of technological advancements. Within your company, I hope to have played a key role in developing and launching at least one major AI-driven product that significantly impacts the industry. My aim is not just to advance my career, but to contribute meaningfully to the company's growth and innovation in the AI sector.",
                        'overall_score' => 60,
                        'content_score' => 55,
                        'language_score' => 65,
                        'structure_score' => 60,
                        'overall_feedback' => "Your answer shows some forward-thinking, but it's quite general and focuses mainly on personal benefits. Try to be more specific about your career goals and how they align with the company's objectives. Discuss particular skills or areas of expertise you want to develop, and how these could benefit the company. Also, consider mentioning any industry trends or technologies you're interested in pursuing.",
                        'errors' => [
                            [
                                'type' => 'content',
                                'original' => 'I want to have a good job',
                                'correction' => 'I envision myself as a senior software developer, potentially leading a team in innovative AI projects',
                                'explanation' => 'Be more specific about your career aspirations',
                            ],
                            [
                                'type' => 'structure',
                                'original' => 'maybe be a manager',
                                'correction' => 'I aim to develop leadership skills, possibly taking on a team lead or management role',
                                'explanation' => 'Express your goals more confidently and tie them to skill development',
                            ],
                            [
                                'type' => 'content',
                                'original' => 'I hope to learn new things',
                                'correction' => "I plan to deepen my expertise in machine learning and data analytics, possibly pursuing a master's degree part-time",
                                'explanation' => 'Specify what you want to learn and how you plan to achieve it',
                            ],
                            [
                                'type' => 'content',
                                'original' => 'make more money',
                                'correction' => "My aim is to contribute meaningfully to the company's growth and innovation in the AI sector",
                                'explanation' => 'Focus on value creation rather than personal financial gain in your answer',
                            ],
                        ],
                    ]),
                ],
                'ja' => [
                    'question' => '5年後、あなたはどのようになっていたいですか？',
                    'answer' => '良い仕事に就いて、たぶん管理職になりたいです。新しいことを学び、もっと稼ぎたいです。',
                    'evaluation' => json_encode([
                        'improved_answer' => '5年後、私はシニアソフトウェア開発者として、革新的なAIプロジェクトのチームをリードしていることを目指しています。機械学習とデータ分析の専門知識を深め、この目標をサポートするために、可能であればパートタイムで修士号の取得も考えています。また、技術の最前線に立ち続けるために、AI分野のオープンソースプロジェクトへの貢献にも熱心です。御社では、業界に大きな影響を与えるAI駆動の主要製品の開発と立ち上げに少なくとも1つ、重要な役割を果たしていることを期待しています。私の目標は単にキャリアを前進させることだけでなく、AI分野における会社の成長とイノベーションに意義ある貢献をすることです。',
                        'overall_score' => 60,
                        'content_score' => 55,
                        'language_score' => 65,
                        'structure_score' => 60,
                        'overall_feedback' => '将来を見据えた回答ではありますが、非常に一般的で、主に個人的な利益に焦点を当てています。キャリア目標をより具体的に述べ、それらが会社の目標とどのように一致するかを説明するよう心がけてください。開発したい特定のスキルや専門分野について議論し、それらがどのように会社に利益をもたらすかを説明してください。また、追求したい業界のトレンドや技術についても言及することを検討してください。',
                        'errors' => [
                            [
                                'type' => 'content',
                                'original' => '良い仕事に就いて',
                                'correction' => 'シニアソフトウェア開発者として、革新的なAIプロジェクトのチームをリードしていることを目指しています',
                                'explanation' => 'キャリアの抱負をより具体的に述べてください',
                            ],
                            [
                                'type' => 'structure',
                                'original' => 'たぶん管理職になりたいです',
                                'correction' => 'リーダーシップスキルを磨き、チームリーダーや管理職の役割を担うことを目指しています',
                                'explanation' => '目標をより自信を持って表現し、スキル開発と結びつけてください',
                            ],
                            [
                                'type' => 'content',
                                'original' => '新しいことを学び',
                                'correction' => '機械学習とデータ分析の専門知識を深め、可能であればパートタイムで修士号の取得も考えています',
                                'explanation' => '学びたいことを具体的に述べ、それをどのように達成するかを説明してください',
                            ],
                            [
                                'type' => 'content',
                                'original' => 'もっと稼ぎたいです',
                                'correction' => 'AI分野における会社の成長とイノベーションに意義ある貢献をすることが私の目標です',
                                'explanation' => '個人的な金銭的利益ではなく、価値創造に焦点を当てた回答をしてください',
                            ],
                        ],
                    ]),
                ],
            ],
            'questions_to_companies' => [
                'en' => [
                    'question' => 'Do you have any questions for us about the company or the role?',
                    'answer' => "What's the work environment like? Is there opportunity for growth? When can I start?",
                    'evaluation' => json_encode([
                        'improved_answer' => "1. Can you tell me more about the team I'd be working with and the current projects they're focusing on?\n2. How does the company approach professional development and learning opportunities for its employees?\n3. What are the biggest challenges the department is currently facing, and how might this role contribute to addressing them?\n4. Can you describe the company's approach to innovation, particularly in AI and machine learning?\n5. How does the company measure success for this position, and what would you expect the person in this role to achieve in the first six months?",
                        'overall_score' => 65,
                        'content_score' => 60,
                        'language_score' => 70,
                        'structure_score' => 65,
                        'overall_feedback' => 'Your questions show basic interest, but they could be more specific and demonstrate deeper research into the company and role. Try to ask questions that show your understanding of the industry and your genuine interest in the position. Avoid questions about basic benefits or start dates at this stage.',
                        'errors' => [
                            [
                                'type' => 'content',
                                'original' => "What's the work environment like?",
                                'correction' => "Can you tell me more about the team I'd be working with and the current projects they're focusing on?",
                                'explanation' => 'Ask more specific questions about the role and team dynamics',
                            ],
                            [
                                'type' => 'structure',
                                'original' => 'Is there opportunity for growth?',
                                'correction' => 'How does the company approach professional development and learning opportunities for its employees?',
                                'explanation' => 'Frame the question more professionally and ask for specific details',
                            ],
                            [
                                'type' => 'content',
                                'original' => 'When can I start?',
                                'correction' => 'How does the company measure success for this position, and what would you expect the person in this role to achieve in the first six months?',
                                'explanation' => 'Instead of asking about start dates, focus on understanding expectations and success metrics for the role',
                            ],
                        ],
                    ]),
                ],
                'ja' => [
                    'question' => '会社や役割について、何か質問はありますか？',
                    'answer' => '職場環境はどうですか？成長の機会はありますか？いつから始められますか？',
                    'evaluation' => json_encode([
                        'improved_answer' => "1. 私が働くことになるチームと、現在注力しているプロジェクトについて詳しく教えていただけますか？\n2. 従業員の専門能力開発や学習機会について、会社はどのようなアプローチを取っていますか？\n3. 現在、部門が直面している最大の課題は何で、この役割がそれらの課題にどのように貢献できるでしょうか？\n4. 特にAIと機械学習の分野で、会社のイノベーションへのアプローチを教えていただけますか？\n5. この職位の成功をどのように測定していますか？また、最初の6ヶ月でこの役割の人物に期待することは何ですか？",
                        'overall_score' => 65,
                        'content_score' => 60,
                        'language_score' => 70,
                        'structure_score' => 65,
                        'overall_feedback' => '質問は基本的な関心を示していますが、もっと具体的で、会社と役割についてより深い調査を示すことができます。業界への理解と、ポジションへの真摯な関心を示す質問をするよう心がけてください。この段階で基本的な福利厚生や開始日について質問するのは避けてください。',
                        'errors' => [
                            [
                                'type' => 'content',
                                'original' => '職場環境はどうですか？',
                                'correction' => '私が働くことになるチームと、現在注力しているプロジェクトについて詳しく教えていただけますか？',
                                'explanation' => '役割やチームの動態についてより具体的な質問をしてください',
                            ],
                            [
                                'type' => 'structure',
                                'original' => '成長の機会はありますか？',
                                'correction' => '従業員の専門能力開発や学習機会について、会社はどのようなアプローチを取っていますか？',
                                'explanation' => '質問をより専門的に表現し、具体的な詳細を尋ねてください',
                            ],
                            [
                                'type' => 'content',
                                'original' => 'いつから始められますか？',
                                'correction' => 'この職位の成功をどのように測定していますか？また、最初の6ヶ月でこの役割の人物に期待することは何ですか？',
                                'explanation' => '開始日について尋ねるのではなく、役割に対する期待や成功の指標を理解することに焦点を当ててください',
                            ],
                        ],
                    ]),
                ],
            ],
        ];

        return $examples[$questionType] ?? null;
    }

    public function updateStudyTime()
    {
        $service = new InterviewStudyProgressService;
        $service->trackStudyTime(10, 'practice');
    }
}