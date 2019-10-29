<?php

use App\Models\Profile;
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
                'phone' => '0209436736',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emmanuel Fache (Hirer)',
                'password' => bcrypt('password'),
                'email' => 'emradegh@gmail.com',
                'phone' => '0553699868',
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
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->save();
            $user->id == 1 ? $user->assignRole('freelancer') : $user->assignRole('hirer');
        }
    }
}
