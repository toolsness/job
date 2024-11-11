<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notice;
use Carbon\Carbon;

class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
