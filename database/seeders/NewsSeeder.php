<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
}
