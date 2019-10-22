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
                'name' => 'employment'
            ],
            [
                'name' => 'marketing'
            ],
            [
                'name' => 'branding'
            ],
            [
                'name' => 'design'
            ],
        ];

        foreach ($tags as $tag) {
            BlogTag::create($tag);
        }
    }
}
