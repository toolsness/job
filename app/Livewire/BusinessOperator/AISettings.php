<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Setting;
use App\Models\CandidateTokenUsage;

class AISettings extends Component
{
    public $developerMode = false;
    public $aiRegion = 'hongkong';
    public $japanProxyUrls = [
        'chat' => '',
        'speech' => '',
        'transcription' => '',
    ];
    public $japanApiKey = '';
    public $candidatesUsage;
    public $useCustomPrompts = [];
    public $aiModels = [];
    public $systemPrompts = [];

    public $showAiModelsInfo = [];
    public $showSystemPromptsInfo = [];

    protected $rules = [
        'aiRegion' => 'required|in:hongkong,japan',
        'japanProxyUrls.chat' => 'required_if:aiRegion,japan|url',
        'japanProxyUrls.speech' => 'required_if:aiRegion,japan|url',
        'japanProxyUrls.transcription' => 'required_if:aiRegion,japan|url',
        'japanApiKey' => 'required_if:aiRegion,japan',
    ];

    public function mount()
    {
        $this->aiRegion = Setting::get('ai_region', 'hongkong');
        $this->japanProxyUrls = [
            'chat' => Setting::get('japan_proxy_url.chat', ''),
            'speech' => Setting::get('japan_proxy_url.speech', ''),
            'transcription' => Setting::get('japan_proxy_url.transcription', ''),
        ];
        $this->japanApiKey = Setting::get('japan_api_key', '');
        $this->useCustomPrompts = Setting::get('use_custom_prompts', []);
        $this->aiModels = Setting::get('ai_models', []);
        $this->systemPrompts = Setting::get('system_prompts', []);
        $this->loadCandidatesUsage();

        $segments = ['ai_voice_interview_test', 'ai_interview_answer_practice', 'ai_self_promotion_creator_cv'];
        // $segments = ['ai_voice_interview_test', 'ai_written_interview_test', 'ai_interview_answer_practice', 'ai_self_promotion_creator_cv'];

        foreach ($segments as $segment) {
            $this->showAiModelsInfo[$segment] = false;
            $this->showSystemPromptsInfo[$segment] = false;
        }
        $this->developerMode = Setting::get('developer_mode', false);
    }

    public function toggleDeveloperMode()
    {
        $this->developerMode = !$this->developerMode;
        Setting::set('developer_mode', $this->developerMode);

        if ($this->developerMode) {
            flash()->info('Developer Mode Activated');
        } else {
            flash()->info('Developer Mode Deactivated');
        }
    }

    public function loadCandidatesUsage()
    {
        $this->candidatesUsage = CandidateTokenUsage::with(['candidate.student.user'])
            ->orderBy('monthly_token_usage', 'desc')
            ->get();
    }

    public function saveAIRegionSettings()
    {
        $this->validate();

        Setting::set('ai_region', $this->aiRegion);
        Setting::set('japan_proxy_url.chat', $this->japanProxyUrls['chat']);
        Setting::set('japan_proxy_url.speech', $this->japanProxyUrls['speech']);
        Setting::set('japan_proxy_url.transcription', $this->japanProxyUrls['transcription']);
        Setting::set('japan_api_key', $this->japanApiKey);

        flash()->success('AI region settings updated successfully.');
    }

    public function saveCustomPromptSettings($segment)
    {
        Setting::set("use_custom_prompts.$segment", $this->useCustomPrompts[$segment] ?? false);
        Setting::set("ai_models.$segment", $this->aiModels[$segment] ?? '');
        Setting::set("system_prompts.$segment", $this->systemPrompts[$segment] ?? '');

        flash()->success("Custom prompt settings for $segment updated successfully.");
    }

    public function render()
    {
        return view('livewire.business-operator.a-i-settings');
    }
}
