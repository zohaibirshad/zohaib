<?php

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
            [
                'title' => 'Android',
            ],
            [
                'title' => 'iOS',
            ],
            [
                'title' => 'copywriting',
            ],
            [
                'title' => 'translating',
            ],
            [
                'title' => 'editing',
            ],
            [
                'title' => 'frontend',
            ],
            [ 
                'title' => 'angualar',
            ],
            [
                'title' => 'vue',
            ],
            [
                'title' => 'web apps',
            ],
            [
                'title' => 'design',
            ],
            [
                'title' => 'wordpress',
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
