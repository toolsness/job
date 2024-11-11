<?php

use App\Http\Controllers\Company\EmailVerifyController as CompanyEmailVerification;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InterviewPracticeController;
use App\Http\Controllers\Student\EmailVerifyController as StudentEmailVerification;
use App\Http\Controllers\TestLoginController;
use App\Livewire\BusinessOperator\CreateJobSeeker;
use App\Livewire\BusinessOperator\CreateStudent;
use App\Livewire\BusinessOperator\CreateVacancy;
use App\Livewire\BusinessOperator\CreateVRContent;
use App\Livewire\BusinessOperator\EditJobSeeker;
use App\Livewire\BusinessOperator\EditProfile as BusinessOperatorEditProfile;
use App\Livewire\BusinessOperator\EditStudent;
use App\Livewire\BusinessOperator\EditVacancy;
use App\Livewire\BusinessOperator\EditVRContent;
use App\Livewire\BusinessOperator\InterviewCreate;
use App\Livewire\BusinessOperator\InterviewEdit;
use App\Livewire\BusinessOperator\InterviewList;
use App\Livewire\BusinessOperator\JobSeekerList;
use App\Livewire\BusinessOperator\StudentList;
use App\Livewire\BusinessOperator\VacancyList;
use App\Livewire\BusinessOperator\VRContentList;
use App\Livewire\Common\ConfirmScouting;
use App\Livewire\Common\CreateJobListing;
use App\Livewire\Common\InterviewDetails;
use App\Livewire\Common\JobInterviewList;
use App\Livewire\Common\JobSeekerSearch;
use App\Livewire\Common\JobSeekerSearchView;
use App\Livewire\Common\ListJobRegistrationInformation;
use App\Livewire\Common\ListJobRegistrationInformationDetails;
use App\Livewire\Common\ListJobRegistrationInformationDetailsVrContent;
use App\Livewire\Common\SelectJobForScouting;
use App\Livewire\Company\Admin\ApproveRepresentatives;
use App\Livewire\Company\Auth\ForgotPassword;
use App\Livewire\Company\Auth\Login as CompanyLogin;
use App\Livewire\Company\Auth\NewMemberRegistration as CompanyNewMemberRegistration;
use App\Livewire\Company\Auth\ResetPassword;
use App\Livewire\Company\Auth\UserRegistration as CompanyUserRegistration;
use App\Livewire\Company\EditCompanyInfo;
use App\Livewire\Company\EditProfile as CompanyEditProfile;
use App\Livewire\Company\InterviewScheduleManagement;
use App\Livewire\Company\VRContentDetails as CompanyVRContentDetails;
use App\Livewire\Company\VRContentList as CompanyVRContentList;
use App\Livewire\Messages;
use App\Livewire\Student\Auth\Login as StudentLogin;
use App\Livewire\Student\Auth\NewMemberRegistration as StudentNewMemberRegistration;
use App\Livewire\Student\Auth\UserRegistration as StudentUserRegistration;
use App\Livewire\Student\CandidateDetails;
use App\Livewire\Student\EditProfile as StudentEditProfile;
use App\Livewire\Student\InterviewAnswerEvaluation;
use App\Livewire\Student\InterviewAnswerPractice;
use App\Livewire\Student\InterviewAnswerWriting;
use App\Livewire\Student\InterviewApplicationTimeSelection;
use App\Livewire\Student\InterviewConfirmation;
use App\Livewire\Student\InterviewPreparationStudyPlan;
use App\Livewire\Student\Orientation;
use App\Livewire\Student\ProfileCvCreation;
use App\Livewire\Student\SelfPromotionCvCreation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::prefix('business-operator')->group(function () {
    Route::get('/login', \App\Livewire\BusinessOperator\Auth\Login::class)->name('business-operator.login');
    Route::get('/register', \App\Livewire\BusinessOperator\Auth\Register::class)->name('business-operator.register');
    Route::get('/forgot-password', \App\Livewire\BusinessOperator\Auth\ForgotPassword::class)->name('business-operator.password.request');
    Route::post('/forgot-password', [\App\Livewire\BusinessOperator\Auth\ForgotPassword::class, 'sendResetLink'])->name('business-operator.password.email');
    Route::get('/reset-password/{token}', \App\Livewire\BusinessOperator\Auth\ResetPassword::class)->name('business-operator.password.reset');
    Route::post('/reset-password', [\App\Livewire\BusinessOperator\Auth\ResetPassword::class, 'resetPassword'])->name('business-operator.password.update');
});

Route::middleware(['auth', 'check.user.type:BusinessOperator'])->group(function () {
    Route::get('/business-operator/ai-settings', \App\Livewire\BusinessOperator\AISettings::class)->name('business-operator.ai-settings');
    Route::get('/business-operator/edit-profile', BusinessOperatorEditProfile::class)->name('business-operator.edit-profile');

    // Company routes
    Route::get('/business-operator/companies', \App\Livewire\BusinessOperator\CompanyList::class)->name('business-operator.companies');
    Route::get('/business-operator/companies/create', \App\Livewire\BusinessOperator\CreateCompany::class)->name('business-operator.companies.create');
    Route::get('/business-operator/companies/{company}/edit', \App\Livewire\BusinessOperator\EditCompany::class)->name('business-operator.companies.edit');

    // Company user routes
    Route::get('/business-operator/company-users', \App\Livewire\BusinessOperator\CompanyUserList::class)->name('business-operator.company-users');
    Route::get('/business-operator/company-users/create', \App\Livewire\BusinessOperator\CreateCompanyUser::class)->name('business-operator.company-users.create');
    Route::get('/business-operator/company-users/{user}/edit', \App\Livewire\BusinessOperator\EditCompanyUser::class)->name('business-operator.company-users.edit');

    // Student routes
    Route::get('/business-operator/students', StudentList::class)->name('business-operator.students.index');
    Route::get('/business-operator/students/create', CreateStudent::class)->name('business-operator.students.create');
    Route::get('/business-operator/students/{student}/edit', EditStudent::class)->name('business-operator.students.edit');

    // Job Seeker routes
    Route::get('/business-operator/job-seekers', JobSeekerList::class)->name('business-operator.job-seekers.index');
    Route::get('/business-operator/job-seekers/create', CreateJobSeeker::class)->name('business-operator.job-seekers.create');
    Route::get('/business-operator/job-seekers/{jobSeeker}/edit', EditJobSeeker::class)->name('business-operator.job-seekers.edit');

    // Vacancy routes
    Route::get('/business-operator/vacancies', VacancyList::class)->name('business-operator.vacancies.index');
    Route::get('/business-operator/vacancies/create', CreateVacancy::class)->name('business-operator.vacancies.create');
    Route::get('/business-operator/vacancies/{vacancy}/edit', EditVacancy::class)->name('business-operator.vacancies.edit');

    // VR Content routes
    Route::get('/business-operator/vr-contents', VRContentList::class)->name('business-operator.vr-contents.index');
    Route::get('/business-operator/vr-contents/create', CreateVRContent::class)->name('business-operator.vr-contents.create');
    Route::get('/business-operator/vr-contents/{vrContent}/edit', EditVRContent::class)->name('business-operator.vr-contents.edit');

    // New Interview routes
    Route::get('/business-operator/interviews', InterviewList::class)->name('business-operator.interviews.index');
    // Route::get('/business-operator/interviews/create', InterviewCreate::class)->name('business-operator.interviews.create');
    Route::get('/business-operator/interviews/{interview}/edit', InterviewEdit::class)->name('business-operator.interviews.edit');

    // Business Operator routes
    Route::get('/business-operator/business-operators', \App\Livewire\BusinessOperator\BusinessOperatorList::class)->name('business-operator.business-operators.index');
    Route::get('/business-operator/business-operators/create', \App\Livewire\BusinessOperator\CreateBusinessOperator::class)->name('business-operator.business-operators.create');
    Route::get('/business-operator/business-operators/{businessOperator}/edit', \App\Livewire\BusinessOperator\EditBusinessOperator::class)->name('business-operator.business-operators.edit');

    // Vacancy Category routes
    Route::get('/business-operator/vacancy-categories', App\Livewire\BusinessOperator\VacancyCategoryList::class)->name('business-operator.vacancy-categories');

    // Qualification routes
    Route::get('/business-operator/qualifications-management', \App\Livewire\BusinessOperator\QualificationManager::class)->name('business-operator.qualifications.management');

    // News Notice Management
    Route::get('/business-operator/news-notices', \App\Livewire\BusinessOperator\NewsNoticeManager::class)->name('business-operator.news-notices');

});

Route::middleware(['auth', 'check.user.type:BusinessOperator'])->group(function () {
    Route::get('/business-operator/approve', \App\Livewire\BusinessOperator\ApproveBusinessOperators::class)->name('business-operator.approve');
});

// Test Login Routes
Route::get('/test-login', [TestLoginController::class, 'index'])->name('test.login.index');
Route::get('/test-login/{id}', [TestLoginController::class, 'login'])->name('test.login');

//Testing send a Mail
// Route::get('/email/{mail}', function ($mail) {
//     Mail::to($mail)->send(new \App\Mail\PasswordResetSuccessfulMail());
//     return 'Test Email sent!';
// })
// ->name('mail.test');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::prefix('student')->group(function () {
        Route::get('/new-member-registration', StudentNewMemberRegistration::class)->name('student.new-member-registration');
        Route::get('/user-registration', StudentUserRegistration::class)->name('student.user-registration');
        Route::get('/login', StudentLogin::class)->name('student.login');
        Route::get('forgot-password', \App\Livewire\Student\Auth\ForgotPassword::class)->name('student.password.request');
        Route::post('forgot-password', [\App\Livewire\Student\Auth\ForgotPassword::class, 'sendResetLink'])->name('student.password.email');
        Route::get('reset-password/{token}', \App\Livewire\Student\Auth\ResetPassword::class)->name('student.password.reset');
        Route::post('reset-password', [\App\Livewire\Student\Auth\ResetPassword::class, 'resetPassword'])->name('student.password.update');
    });

    Route::prefix('company')->group(function () {
        Route::get('/new-member-registration', CompanyNewMemberRegistration::class)->name('company.new-member-registration');
        Route::get('/user-registration', CompanyUserRegistration::class)->name('company.user-registration');
        Route::get('/login', CompanyLogin::class)->name('company.login');
        Route::get('forgot-password', ForgotPassword::class)->name('company.password.request');
        Route::post('forgot-password', [ForgotPassword::class, 'sendResetLink'])->name('company.password.email');
        Route::get('reset-password/{token}', ResetPassword::class)->name('company.password.reset');
        Route::post('reset-password', [ResetPassword::class, 'resetPassword'])->name('company.password.update');
    });
});

// Email Verification Routes
Route::get('student/email/verify/{token}', StudentEmailVerification::class)->name('student.email.verify');
Route::get('company/email/verify/{token}', CompanyEmailVerification::class)->name('company.email.verify');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Route::get('/interview-practice/voice', \App\Livewire\Student\InterviewPracticeVoice::class)->name('interview-practice.voice');
    Route::middleware('check.user.type:Student,Candidate')->group(function () {
        Route::get('/interview-practice/voice', [InterviewPracticeController::class, 'index'])->name('interview-practice.voice');
        Route::post('/interview-practice/voice', [InterviewPracticeController::class, 'store'])
    ->name('interview-practice.voice.store')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    });

    // Profile Routes
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Message Routes
    Route::get('/messages', Messages::class)->name('messages');

    // Interview Confirmation Routes
    // Route::middleware('check.user.type:Student,Candidate,CompanyRepresentative,CompanyAdmin')->group(function () {
    //     Route::get('/interview-confirmation/{interview}', InterviewConfirmation::class)->name('student.interview.confirmation');
    // });
    Route::get('/interview-confirmation/{interview}', InterviewConfirmation::class)
        ->name('student.interview.confirmation')
        ->middleware('can:viewConfirmation,interview');

    // Student and Candidate Routes
    Route::middleware('check.user.type:Student,Candidate')->group(function () {
        Route::get('/candidate/vacancies/favorite', \App\Livewire\Student\FavoriteVacancyList::class)->name('candidate.vacancies.favorite-list');
        Route::get('/candidate-details/{vacancyId?}/{interviewId?}', CandidateDetails::class)
            ->name('student.candidate-details')
            ->middleware('can:view-candidate-details,vacancyId,interviewId');
        Route::get('/interview-application/{vacancyId?}/{interviewId?}', InterviewApplicationTimeSelection::class)
            ->name('student.interview-application-time-selection')
            ->middleware('can:view-interview-application-time-selection,vacancyId,interviewId');

        Route::get('/interview-preparation-study-plan', InterviewPreparationStudyPlan::class)->name('interview-preparation-study-plan');
        Route::get('/interview-preparation-study-orientation', Orientation::class)->name('interview-preparation-study-orientation');
        Route::get('/cv-creation-profile', ProfileCvCreation::class)->name('cv.creation.profile');
        Route::get('/cv-creation-self-promotion', SelfPromotionCvCreation::class)->name('cv.creation.self-promotion');
        Route::get('/student/profile/edit', StudentEditProfile::class)->name('student.profile.edit');

        Route::prefix('interview-answer')->group(function () {
            Route::get('/writing', InterviewAnswerWriting::class)->name('interview-answer.writing');
            Route::get('/practice', InterviewAnswerPractice::class)->name('interview-answer.practice');
            Route::get('/evaluation', InterviewAnswerEvaluation::class)->name('interview-answer.evaluation');
        });
    });

    // Company Routes
    Route::middleware('check.user.type:CompanyRepresentative,CompanyAdmin')->group(function () {
        Route::get('/company/vr-contents', CompanyVRContentList::class)->name('company.vr-contents.index');
        Route::get('/company/interview-schedule', InterviewScheduleManagement::class)->name('company.interview-schedule');
        Route::get('/job-seeker/search', JobSeekerSearch::class)->name('job-seeker.search');
        Route::get('/company/edit-profile', CompanyEditProfile::class)->name('company.edit-profile');
        Route::get('/job-list/create', CreateJobListing::class)->name('job-list.create');

        Route::get('/vacancy/{vacancy}/interview-schedule', InterviewScheduleManagement::class)->name('company.vacancy.interview-schedule');
        Route::put('/job-details/{id}', [ListJobRegistrationInformationDetails::class, 'save'])->name('job-details.update');

        Route::get('/job-seeker/view/{id}', JobSeekerSearchView::class)->name('job-seeker.view');
        // Route::get('/interview/{interview}', InterviewDetails::class)->name('interview.details');
        // Route::get('/interview/{interview}', InterviewDetails::class)->name('candidate.interview.details');

        Route::get('/company/vr-contents/{vrContent}', CompanyVRContentDetails::class)->name('company.vr-contents.details');

    });

    Route::middleware('check.user.type:CompanyRepresentative,CompanyAdmin,Candidate,Student')->group(function () {
        Route::get('/job-interviews', JobInterviewList::class)->name('job-interviews');
        Route::get('/interview/{interview}', InterviewDetails::class)->name('interview.details')->middleware('can:viewConfirmation,interview');

    });

    // Company Admin Routes
    Route::middleware('check.user.type:CompanyAdmin')->group(function () {
        Route::get('/company/representatives-list', ApproveRepresentatives::class)->name('company.approve-representatives');
        Route::get('/company/edit-company-info', EditCompanyInfo::class)->name('company.edit-company-info');

    });

    // Shared Routes
    Route::middleware(['auth', 'check.user.type:CompanyRepresentative,CompanyAdmin,Student,Candidate'])->group(function () {
        Route::get('/job-list/search', ListJobRegistrationInformation::class)->name('job-list.search');
        Route::get('/job-details/{id}', ListJobRegistrationInformationDetails::class)->name('job-details');
        Route::get('/job/{vacancyId}/vr-content/{contentType}', ListJobRegistrationInformationDetailsVrContent::class)
            ->name('job.vr-content')
            ->middleware('can:view-vr-content,App\Models\VRContent');
        Route::put('/job/{vacancyId}/vr-content/{contentType}', [ListJobRegistrationInformationDetailsVrContent::class, 'save'])
            ->name('job.vr-content.update')
            ->middleware('can:update-vr-content,App\Models\VRContent');
    });
});

// Scouting Routes (These might need authentication middleware)
Route::middleware(['auth', 'check.user.type:CompanyRepresentative,CompanyAdmin'])->group(function () {
    Route::get('/select-job/{candidateId}', SelectJobForScouting::class)
        ->name('job-seeker.select-job')
        ->middleware('can:scout,App\Models\Candidate');
    Route::get('/confirm-scouting/{candidateId}/{jobId}', ConfirmScouting::class)
        ->name('job-seeker.confirm-scouting')
        ->middleware('can:scout,App\Models\Candidate')
        ->middleware('can:scoutWith,App\Models\Vacancy');
});

// Dynamic Routes (at the end)
Route::get('/', function (Request $request) {
    $viewType = $request->input('viewType', Cookie::get('default_view', 'student'));

    return view('home.index', compact('viewType'));
})->name('home');

Route::get('/student', function () {
    return view('home.index', ['viewType' => 'student']);
})->name('student.home');

Route::get('/company', function () {
    return view('home.index', ['viewType' => 'company']);
})->name('company.home');

require __DIR__.'/auth.php';
