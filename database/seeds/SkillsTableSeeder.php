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
                'name' => 'Android',
            ],
            [
                'name' => 'iOS',
            ],
            [
                'name' => 'copywriting',
            ],
            [
                'name' => 'translating',
            ],
            [
                'name' => 'editing',
            ],
            [
                'name' => 'frontend',
            ],
            [ 
                'name' => 'angualar',
            ],
            [
                'name' => 'vue',
            ],
            [
                'name' => 'web apps',
            ],
            [
                'name' => 'design',
            ],
            [
                'name' => 'wordpress',
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
