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
        Maatwebsite\Excel\Facades\Excel::import(new App\Imports\SkillImport, storage_path('skillset.csv'));

    }
}
