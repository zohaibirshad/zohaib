<?php

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Freelancing'
            ],
            [
                'name' => 'Entrepreneurship'
            ],
            [
                'name' => 'Development'
            ],
            [
                'name' => 'Design'
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }
    }
}
