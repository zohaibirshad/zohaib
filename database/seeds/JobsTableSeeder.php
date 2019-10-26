<?php

use App\Models\Job;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobs = [
            [
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'industry_id' => 1,
                'country_id' => 80,
                'job_budget_id' => 1,
                'name' => $name = 'Food Delivery Mobile Application',
                'slug' => str_slug($name),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            <br>Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            <br>Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '6',
                'featured' => 'yes',
                'status' => 'not assigned',
            ],
            [
                'user_id' => 1,
                'uuid' => Str::uuid(),
                'industry_id' => 5,
                'country_id' => 80,
                'job_budget_id' => 2,
                'name' => $name = '2000 Words English to German',
                'slug' => str_slug($name),
                'description' => 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.

                            <br>Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.
            
                            <br>Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.',
                'duration' => '2',
                'featured' => 'yes',
                'status' => 'not assigned',
            ],

        ];


        foreach ($jobs as $j) {
            $job = Job::create($j);

            if ($job->id == 1) {
                $job->skills()->attach([1, 2]);
            } else {
                $job->skills()->attach([3, 4, 5]);
            }
        }
    }
}
