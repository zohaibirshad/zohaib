<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountriesTableSeeder::class,
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            BlogTagsTableSeeder::class,
            BlogCategoriesTableSeeder::class,
            BlogPostsTableSeeder::class,
        ]);
    }
}
