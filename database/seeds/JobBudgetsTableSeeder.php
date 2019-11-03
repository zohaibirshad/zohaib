<?php

use App\Models\JobBudget;
use Illuminate\Database\Seeder;

class JobBudgetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $budgets = [
            [
                'name' => 'Small Project',
                'from' => '75',
                'to' => '200',
                'type' => 'fixed',
            ],
            [
                'name' => 'Meduim Project',
                'from' => '1000',
                'to' => '2000',
                'type' => 'fixed',
            ],
        ];

        foreach ($budgets as $budget) {
            JobBudget::create($budget);
        }
    }
}
