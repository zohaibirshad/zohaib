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
                'name' => $name = 'Freelancing',
                'slug' => str_slug($name),
            ],
            [
                'name' => $name = 'Entrepreneurship',
                'slug' => str_slug($name),
            ],
            [
                'name' => $name = 'Development',
                'slug' => str_slug($name),
            ],
            [
                'name' => $name = 'Design',
                'slug' => str_slug($name),
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }
    }
}
