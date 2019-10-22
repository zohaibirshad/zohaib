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
        ];
        
        foreach($users as $user){
            $user = User::create($user);
            $user->assignRole('freelancer');
        }
    }
}
