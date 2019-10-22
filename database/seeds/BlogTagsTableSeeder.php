<?php

use App\Models\BlogTag;
use Illuminate\Database\Seeder;

class BlogTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            [
                'name' => $name = 'employment',
                'slug' => str_slug($name),
            ],
            [
                'name' => $name = 'marketing',
                'slug' => str_slug($name),
            ],
            [
                'name' => $name = 'branding',
                'slug' => str_slug($name),
            ],
            [
                'name' => $name = 'design',
                'slug' => str_slug($name),
            ],
        ];

        foreach ($tags as $tag) {
            BlogTag::create($tag);
        }
    }
}
