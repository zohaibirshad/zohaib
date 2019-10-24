<?php

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            [
                'name' => 'Admin Support',
                'featured' => 'yes',
            ],
            [
                'name' => 'Customer Service',
                'featured' => 'yes',
            ],
            [
                'name' => 'Data Analytics',
                'featured' => 'yes',
            ],
            [
                'name' => 'Graphics Design',
                'featured' => 'yes',
            ],
            [
                'name' => 'Software Development',
                'featured' => 'yes',
            ],
            [
                'name' => 'IT & Networking',
                'featured' => 'yes',
            ],
            [
                'name' => 'Writing',
                'featured' => 'yes',
            ],
            [
                'name' => 'Translation',
                'featured' => 'yes',
            ],
            [
                'name' => 'Sales & Marketing',
                'featured' => 'yes',
            ],
        ];

        foreach ($industries as $industry) {
            Industry::create($industry);
        }
    }
}
