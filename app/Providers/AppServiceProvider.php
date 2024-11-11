<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Common\JobInterviewList;
use App\Livewire\Messages;
use App\Policies\VacancyPolicy;
use App\Policies\CandidatePolicy;
use App\Policies\InterviewPolicy;
use App\Policies\VRContentPolicy;
use App\Models\VRContent;
use App\Models\Vacancy;
use App\Models\Student;
use App\Models\Candidate;
use App\Models\CompanyRepresentative;
use App\Models\CompanyAdmin;
use Illuminate\Support\Facades\Gate;
use App\Models\Interview;
use App\Livewire\UnreadMessageCount;
use App\Livewire\Home\Partials\ProfileNav;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('unread-message-count', UnreadMessageCount::class);
        Livewire::component('messages', Messages::class);
        Livewire::component('job-interview-list', JobInterviewList::class);
        Livewire::component('home.partials.profile-nav', ProfileNav::class);

        // Register policies
        Gate::policy(Interview::class, InterviewPolicy::class);
        Gate::policy(Vacancy::class, VacancyPolicy::class);
        Gate::policy(Candidate::class, CandidatePolicy::class);
        Gate::policy(VRContent::class, VRContentPolicy::class);

        Gate::define('view-candidate-details', [CandidatePolicy::class, 'viewCandidateDetails']);
        Gate::define('view-interview-application-time-selection', [InterviewPolicy::class, 'viewInterviewApplicationTimeSelection']);

        // Define gates for specific actions
        Gate::define('view-vacancy', [VacancyPolicy::class, 'view']);
        Gate::define('create-vacancy', [VacancyPolicy::class, 'create']);
        Gate::define('update-vacancy', [VacancyPolicy::class, 'update']);
        Gate::define('delete-vacancy', [VacancyPolicy::class, 'delete']);
        Gate::define('view-job-details', [VacancyPolicy::class, 'viewJobDetails']);
        Gate::define('view-job-list-search', [VacancyPolicy::class, 'viewJobListSearch']);

        Gate::define('scout', [CandidatePolicy::class, 'scout']);
        Gate::define('scoutWith', [VacancyPolicy::class, 'scoutWith']);

        Gate::define('view-interview', [InterviewPolicy::class, 'view']);
        Gate::define('create-interview', [InterviewPolicy::class, 'create']);
        Gate::define('update-interview', [InterviewPolicy::class, 'update']);
        Gate::define('delete-interview', [InterviewPolicy::class, 'delete']);
        Gate::define('view-interview-confirmation', [InterviewPolicy::class, 'viewConfirmation']);

        // Gate::define('view-vr-content', [VRContentPolicy::class, 'view']);
        // Gate::define('create-vr-content', [VRContentPolicy::class, 'create']);
        // Gate::define('update-vr-content', [VRContentPolicy::class, 'update']);
        // Gate::define('delete-vr-content', [VRContentPolicy::class, 'delete']);
        Gate::define('viewVRContent', [VRContentPolicy::class, 'viewVRContent']);
        Gate::define('updateVRContent', [VRContentPolicy::class, 'updateVRContent']);

        Gate::define('view-candidate', [CandidatePolicy::class, 'view']);
        Gate::define('create-candidate', [CandidatePolicy::class, 'create']);
        Gate::define('update-candidate', [CandidatePolicy::class, 'update']);
        Gate::define('delete-candidate', [CandidatePolicy::class, 'delete']);
    }
}
