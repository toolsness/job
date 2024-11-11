<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Company;
use App\Models\CompanyRepresentative;
use App\Models\BusinessOperator;
use App\Models\VacancyCategory;
use App\Models\Vacancy;
use App\Models\Candidate;
use App\Models\CompanyAdmin;
use App\Models\Interview;
use App\Models\InterviewSchedule;
use App\Models\Message;
use App\Models\VRContent;
use App\Models\Qualification;
use App\Models\QualificationCategory;
use App\Models\Country;
use App\Models\JapaneseStudyApplication;
use App\Models\JapaneseStudyCourse;
use App\Models\InterviewStudy;
use App\Models\IndustryType;
use App\Models\StudentJapaneseCourseCompletion;
use App\Models\InterviewMemo;
use App\Models\InterviewTimeSlot;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Seed basic data
        $this->call([
            NewsSeeder::class,
            NoticeSeeder::class,
            CountrySeeder::class,
        ]);

        // // Create countries
        // // $countries = Country::factory(50)->create();

        // // Create industry types
        // $industryTypes = IndustryType::factory(10)->create();

        // // Create companies
        // $companies = Company::factory(30)->create();

        // // Create qualification categories
        // $qualificationCategories = QualificationCategory::factory(10)->create();

        // // Create qualifications
        // $qualifications = Qualification::factory(30)->create();

        // // Create VR contents
        // $vrContents = VRContent::factory(20)->create();

        // // Create Japanese study courses
        // $japaneseStudyCourses = JapaneseStudyCourse::factory(15)->create();

        // // Create company admins and representatives
        // foreach ($companies as $company) {
        //     // Create company admin
        //     $adminUser = User::factory()->create([
        //         'user_type' => 'CompanyAdmin',
        //         'login_permission_category' => 'Allowed',
        //     ]);
        //     CompanyAdmin::factory()->create([
        //         'user_id' => $adminUser->id,
        //         'company_id' => $company->id,
        //     ]);

        //     // Create company representatives (1-3 per company)
        //     $numRepresentatives = rand(1, 3);
        //     for ($i = 0; $i < $numRepresentatives; $i++) {
        //         $repUser = User::factory()->create([
        //             'user_type' => 'CompanyRepresentative',
        //             'login_permission_category' => 'Allowed',
        //         ]);
        //         CompanyRepresentative::factory()->create([
        //             'user_id' => $repUser->id,
        //             'company_id' => $company->id,
        //         ]);
        //     }
        // }

        // // Create students and candidates
        // $students = Student::factory(50)->create();
        // foreach ($students as $student) {
        //     if ($student->id <= 30) {
        //         Candidate::factory()->create(['student_id' => $student->id]);
        //     }
        // }

        // // Create vacancies and related data
        // $companyRepresentatives = CompanyRepresentative::all();
        // foreach ($companyRepresentatives as $representative) {
        //     $vacancies = Vacancy::factory(rand(1, 5))->create([
        //         'company_representative_id' => $representative->id,
        //     ]);

        //     foreach ($vacancies as $vacancy) {
        //         InterviewSchedule::factory(rand(3, 7))->create(['vacancy_id' => $vacancy->id]);
        //         $interviews = Interview::factory(rand(1, 3))->create(['vacancy_id' => $vacancy->id]);

        //         foreach ($interviews as $interview) {
        //             InterviewMemo::factory(rand(1, 3))->create([
        //                 'interview_id' => $interview->id,
        //                 'user_id' => $representative->user_id,
        //             ]);
        //         }

        //         // Create InterviewTimeSlots for each vacancy
        //         InterviewTimeSlot::factory(rand(5, 10))->create([
        //             'company_id' => $representative->company_id,
        //             'user_id' => $representative->user_id,
        //             'vacancy_id' => $vacancy->id,
        //         ]);
        //     }

        //     // Create some InterviewTimeSlots without a specific vacancy
        //     InterviewTimeSlot::factory(rand(3, 7))->create([
        //         'company_id' => $representative->company_id,
        //         'user_id' => $representative->user_id,
        //         'vacancy_id' => null,
        //     ]);
        // }

        // // Create additional data
        // JapaneseStudyApplication::factory(30)->create();
        // InterviewStudy::factory(50)->create();
        // StudentJapaneseCourseCompletion::factory(25)->create();
        // Message::factory(100)->create();

        // Create business operators
        $sayed = User::factory()->create([ 'name' => 'Sayed', 'email' => 'abusayedofficialbd@gmail.com', 'user_type' => 'BusinessOperator', 'login_permission_category' => 'Allowed', 'password' => Hash::make('password')]);
        BusinessOperator::factory()->create(['user_id' => $sayed->id, 'name_kanji' => '月曜日', 'name_katakana' => 'ソクソク', 'contact_phone_number' => '01925785462']);
    }
}
