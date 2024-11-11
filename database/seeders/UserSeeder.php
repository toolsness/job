<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyAdmin;
use App\Models\CompanyRepresentative;
use App\Models\Candidate;
use App\Models\Student;
use App\Models\BusinessOperator;
use App\Models\Country;
use App\Models\User;
use App\Models\IndustryType;
use App\Models\QualificationCategory;
use App\Models\Qualification;
use App\Models\News;
use App\Models\Notice;
use App\Models\VacancyCategory;
use App\Models\Vacancy;
use App\Models\InterviewTimeSlot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $testers = [
            ['name' => 'Ayano Takeuchi', 'email' => 'takeuchi@tgg.tokyo'],
            ['name' => 'Abu Sayed', 'email' => 'abusayedofficialbd@gmail.com'],
            ['name' => 'Abdullah Md Yusuf', 'email' => 'yusuf.bd02@gmail.com'],
            ['name' => 'Mehedi Hasan', 'email' => 'mehedi4475@gmail.com'],
            ['name' => 'Apu Vai', 'email' => 'apu01seics01@gmail.com'],
            ['name' => 'Tarequl Islam', 'email' => 'm.tarequl95@gmail.com'],
            ['name' => 'Muhammad Abu Sayeed', 'email' => 'm.say33d@gmail.com']
        ];

        // Create Vacancy categories
        $categories = [
            'INFORMATION TECHNOLOGY',
            'HEALTHCARE',
            'EDUCATION',
        ];

        foreach ($categories as $category) {
            VacancyCategory::create(['name' => $category]);
        }

        // Call seeding functions
        $this->seedIndustryTypes();
        $this->seedQualificationCategories();
        $this->seedQualifications();
        $this->seedCountries();

        foreach ($testers as $tester) {
            $this->createTesterData($tester['name'], $tester['email']);
        }

        // News Notices
        $this->seedNews();
        $this->seedNotices();
    }

    private function createTesterData($name, $email)
    {
        // Create Business Operator
        $businessOperator = User::create([
            'name' => Str::upper($name),
            'email' => $email,
            'username' => $this->generateUsername($name),
            'user_type' => 'BusinessOperator',
            'login_permission_category' => 'Allowed',
            'password' => Hash::make('password'),
        ]);

        BusinessOperator::create([
            'user_id' => $businessOperator->id,
            'name_kanji' => '月曜日',
            'name_katakana' => 'ゲツヨウビ',
            'contact_phone_number' => '01925785462',
        ]);

        // Create 2 Students (one will become a Candidate)
        for ($i = 1; $i <= 2; $i++) {
            // Create Student User
            $studentUser = User::create([
                'name' => Str::upper("{$name} Student {$i}"),
                'email' => $email,
                'username' => $this->generateUsername("{$name}Student{$i}"),
                'user_type' => 'Student',
                'login_permission_category' => 'Allowed',
                'password' => Hash::make('password'),
            ]);

            // Create Student
            $student = Student::create([
                'user_id' => $studentUser->id,
                'name_kanji' => '学生',
                'name_katakana' => 'ガクセイ',
                'name_japanese' => '学生',
                'contact_phone_number' => '01234567890',
            ]);

            // For the first student, create a Candidate
            if ($i === 1) {
                Candidate::create([
                    'student_id' => $student->id,
                    'publish_category' => 'Published',
                    'name' => $studentUser->name,
                    'gender' => 'Male',
                    'birth_date' => '1990-01-01',
                    'nationality' => Country::inRandomOrder()->first()->id,
                    'last_education' => 'Bachelor',
                    'work_history' => 'Some work history',
                    'qualification' => Qualification::inRandomOrder()->first()->id,
                    'self_presentation' => 'Self presentation',
                    'personal_preference' => 'Personal preference',
                ]);

                // Update user type to Candidate
                $studentUser->update(['user_type' => 'Candidate']);
            }
        }

        // Create 5 Companies
        for ($i = 1; $i <= 5; $i++) {
            $company = Company::create([
                'name' => Str::upper("{$name} Company {$i}"),
                'name_kanji' => '会社',
                'name_katakana' => 'カイシャ',
                'industry_type_id' => IndustryType::inRandomOrder()->first()->id,
                'address' => 'Company Address',
                'website' => 'https://ess-dev.a-cloud.tech',
                'contact_email' => 'contact@a-cloud.tech',
                'contact_phone' => '01234567890',
            ]);

            // Create Company Admin
            $companyAdmin = User::create([
                'name' => Str::upper("{$name} Company Admin {$i}"),
                'email' => $email,
                'username' => $this->generateUsername("{$name}CompanyAdmin{$i}"),
                'user_type' => 'CompanyAdmin',
                'login_permission_category' => 'Allowed',
                'password' => Hash::make('password'),
            ]);

            CompanyAdmin::create([
                'user_id' => $companyAdmin->id,
                'company_id' => $company->id,
                'name_kanji' => '管理者',
                'name_katakana' => 'カンリシャ',
                'contact_phone_number' => '09876543210',
            ]);

            // Create Company Representative
            $companyRep = User::create([
                'name' => Str::upper("{$name} Company Rep {$i}"),
                'email' => $email,
                'username' => $this->generateUsername("{$name}CompanyRep{$i}"),
                'user_type' => 'CompanyRepresentative',
                'login_permission_category' => 'Allowed',
                'password' => Hash::make('password'),
            ]);

            CompanyRepresentative::create([
                'user_id' => $companyRep->id,
                'company_id' => $company->id,
                'name_kanji' => '代表者',
                'name_katakana' => 'ダイヒョウシャ',
                'contact_phone_number' => '01122334455',
            ]);

            // Create interview time slots for company admin and representative
            // $this->createInterviewTimeSlots($company->id, $companyAdmin->id);
            // $this->createInterviewTimeSlots($company->id, $companyRep->id);
        }
    }

    // private function createInterviewTimeSlots($companyId, $userId)
    // {
    //     $startDate = Carbon::now() // Start date + 15 days
    //     $endDate = $startDate->copy()->addMonths(3);

    //     while ($startDate <= $endDate) {
    //         for ($i = 1; $i <= 5; $i++) {
    //             $startTime = Carbon::create($startDate->year, $startDate->month, $startDate->day, 9 + $i, 0, 0);
    //             $endTime = $startTime->copy()->addHour();

    //             InterviewTimeSlot::create([
    //                 'company_id' => $companyId,
    //                 'user_id' => $userId,
    //                 'vacancy_id' => null, // You can set this to a specific vacancy if needed
    //                 'date' => $startDate->toDateString(),
    //                 'start_time' => $startTime->toTimeString(),
    //                 'end_time' => $endTime->toTimeString(),
    //                 'status' => 'available',
    //             ]);
    //         }

    //         $startDate->addDay();
    //     }
    // }

    private function generateUsername($name)
    {
        $username = Str::slug($name) . rand(1000, 9999);
        while (User::where('username', $username)->exists()) {
            $username = Str::slug($name) . rand(1000, 9999);
        }
        return $username;
    }

    private function seedIndustryTypes()
    {
        $industryTypes = [
            'Test Industry',
        ];

        foreach ($industryTypes as $type) {
            IndustryType::create(['name' => $type]);
        }
    }

    private function seedQualificationCategories()
    {
        $categories = [
            'ACADEMIC',
            'PROFESSIONAL',
            'TECHNICAL',
            'VOCATIONAL',
            'CERTIFICATION',
            'LANGUAGES',

        ];

        foreach ($categories as $category) {
            QualificationCategory::create(['name' => $category]);
        }
    }

    private function seedQualifications()
    {
        $qualifications = [
            ['name' => 'HIGH SCHOOL', 'category' => 'ACADEMIC'],
            ['name' => 'DIPLOMA', 'category' => 'ACADEMIC'],
            ['name' => 'BACHELOR\'S DEGREE', 'category' => 'ACADEMIC'],
            ['name' => 'MASTER\'S DEGREE', 'category' => 'ACADEMIC'],
            ['name' => 'PHD', 'category' => 'ACADEMIC'],
            ['name' => 'CPA', 'category' => 'PROFESSIONAL'],
            ['name' => 'PMP', 'category' => 'PROFESSIONAL'],
            ['name' => 'CFA', 'category' => 'PROFESSIONAL'],
            ['name' => 'CSC', 'category' => 'PROFESSIONAL'],
            ['name' => 'AWS CERTIFIED SOLUTIONS ARCHITECT', 'category' => 'TECHNICAL'],
            ['name' => 'AWS CERTIFIED DEVELOPER', 'category' => 'TECHNICAL'],
            ['name' => 'AWS CERTIFIED DATA SCIENTIST', 'category' => 'TECHNICAL'],
            ['name' => 'CISCO CCNA', 'category' => 'TECHNICAL'],
            ['name' => 'CISCO CCNP', 'category' => 'TECHNICAL'],
            ['name' => 'CISCO CCIE', 'category' => 'TECHNICAL'],
            ['name' => 'ELECTRICIAN LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'PLUMBER LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'CARPENTER LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'PAINTER LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'WELDER LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'MECHANIC LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'CIVIL ENGINEER LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'ELECTRICAL ENGINEER LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'MECHANICAL ENGINEER LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'FIRE ENGINEER LICENSE', 'category' => 'VOCATIONAL'],
            ['name' => 'PLUMBING CERTIFICATION', 'category' => 'VOCATIONAL'],
            ['name' => 'TOEFL', 'category' => 'CERTIFICATION'],
            ['name' => 'TOEIC', 'category' => 'CERTIFICATION'],
            ['name' => 'IELTS', 'category' => 'CERTIFICATION'],
            ['name' => 'CET', 'category' => 'CERTIFICATION'],
            ['name' => 'IB', 'category' => 'CERTIFICATION'],
            ['name' => 'IBT', 'category' => 'CERTIFICATION'],
            ['name' => 'IBD', 'category' => 'CERTIFICATION'],
            ['name' => 'IBS', 'category' => 'CERTIFICATION'],
            ['name' => 'IBL', 'category' => 'CERTIFICATION'],
            ['name' => 'ENGLISH', 'category' => 'LANGUAGES'],
            ['name' => 'SPANISH', 'category' => 'LANGUAGES'],
            ['name' => 'FRENCH', 'category' => 'LANGUAGES'],
            ['name' => 'GERMAN', 'category' => 'LANGUAGES'],
            ['name' => 'JAPANESE', 'category' => 'LANGUAGES'],
            ['name' => 'CHINESE', 'category' => 'LANGUAGES'],
        ];

        foreach ($qualifications as $qual) {
            $category = QualificationCategory::where('name', $qual['category'])->first();
            Qualification::create([
                'qualification_name' => $qual['name'],
                'qualification_category_id' => $category->id,
            ]);
        }
    }

    private function seedCountries()
    {
        $countries = [
            'JAPAN',
            'UNITED STATES',
            'CHINA',
            'SOUTH KOREA',
            'GERMANY',
            'SAUDI ARABIA',
            'BANGLADESH',
            'UNITED KINGDOM',
            'CANADA',
            'AUSTRALIA',
        ];

        foreach ($countries as $country) {
            Country::create(['country_name' => $country]);
        }
    }

    private function seedNews()
    {
        $news = [
            ['content' => 'New job opening: Software Developer. Learn more at example.com/jobs/1', 'created_at' => Carbon::now()],
            ['content' => 'Job fair next week. Don\'t miss out! Details at example.com/events/1', 'created_at' => Carbon::now()],
            ['content' => 'Featured employer: TechCorp. See their latest job postings at example.com/employers/1', 'created_at' => Carbon::now()],
            ['content' => 'Resume writing tips. Improve your resume with these tips at example.com/resources/1', 'created_at' => Carbon::now()],
            ['content' => 'Interview preparation guide. Be ready for your next interview at example.com/resources/2', 'created_at' => Carbon::now()],
            ['content' => 'Job seeker success story: John Doe. Read about his journey at example.com/success-stories/1', 'created_at' => Carbon::now()],
            ['content' => 'Career advice for remote workers. Adjusting to a remote job? Get tips at example.com/resources/3', 'created_at' => Carbon::now()],
            ['content' => 'Job search strategies for entry-level candidates. Tips at example.com/resources/4', 'created_at' => Carbon::now()],
            ['content' => 'Employer spotlight: GreenTech. Learn about their commitment to sustainability at example.com/employers/2', 'created_at' => Carbon::now()],
            ['content' => 'Job market trends for 2022. Stay informed about the latest trends at example.com/resources/5', 'created_at' => Carbon::now()],
        ];

        foreach ($news as $item) {
            News::create($item);
        }
    }

    private function seedNotices()
    {
        $notices = [
            ['content' => 'Upcoming Japanese language training session. Register at example.com/training', 'created_at' => Carbon::now()],
            ['content' => 'Free webinar: Networking for introverts. Join us on example.com/webinars/1', 'created_at' => Carbon::now()],
            ['content' => 'Job seeker meetup next month. Connect with other job seekers at example.com/events/2', 'created_at' => Carbon::now()],
            ['content' => 'Language learning resources. Improve your language skills with these resources at example.com/resources/6', 'created_at' => Carbon::now()],
            ['content' => 'Career coaching sessions available. Schedule a session at example.com/coaching', 'created_at' => Carbon::now()],
            ['content' => 'Employer workshop: Hiring and retaining top talent. Register at example.com/workshops/1', 'created_at' => Carbon::now()],
            ['content' => 'Job search tips for millennials. Tips at example.com/resources/7', 'created_at' => Carbon::now()],
            ['content' => 'Employer spotlight: EduTech. Learn about their commitment to education at example.com/employers/3', 'created_at' => Carbon::now()],
            ['content' => 'Job market trends for 2023. Stay informed about the latest trends at example.com/resources/8', 'created_at' => Carbon::now()],
            ['content' => 'Free resume review service. Get a free review of your resume at example.com/services/1', 'created_at' => Carbon::now()],
        ];

        foreach ($notices as $item) {
            Notice::create($item);
        }
    }
}
