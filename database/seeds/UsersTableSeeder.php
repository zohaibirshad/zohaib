<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Emmanuel Fache',
                'password' => bcrypt('password'),
                'email' => 'emrade95@gmail.com',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emmanuel Fache (Hirer)',
                'password' => bcrypt('password'),
                'email' => 'emradegh@gmail.com',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emmanuel Oduro',
                'password' => bcrypt('decount'),
                'email' => 'emmarthurson@gmail.com',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emmanuel Oduro (Hirer)',
                'password' => bcrypt('decount'),
                'email' => 'emmanuel@jumeni.com',
                'email_verified_at' => now(),
            ],
            
        ];
        
        foreach($users as $user){
            $user = User::create($user);
            $user->id == 1 ? $user->assignRole('freelancer') : $user->assignRole('hirer');
        }
    }
}
