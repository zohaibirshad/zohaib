<?php

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'title' => $title = 'Free',
                'description' => 'Free',
                'quantity' => 10,
                'recommended' => 0,
                'price' => 0
            ],
            [
                'title' => 'Economy plus',
                'description' => 'Economy plus',
                'quantity' => 20,
                'recommended' => 0,
                'price' => 4.99
            ],
            [
                'title' => $title = 'Business',
                'description' => 'Business',
                'quantity' => 30,
                'recommended' => 1,
                'price' => 9.99
            ],
            [
                'title' => $title = 'First-Class',
                'description' => 'First-Class',
                'quantity' => 40,
                'recommended' => 0,
                'price' => 29.99
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
