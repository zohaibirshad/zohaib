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
            $profile->phone = $user->phone;
            $profile->email = $user->email;
            $profile->country_id = 80;
            $profile->headline = "Flutter Ninja + Laravel Superman";
            $profile->description = "
            <p>Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.</p>
    
                    <p>Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional clickthroughs from DevOps. Nanotechnology immersion along the information highway will close the loop on focusing solely on the bottom line.</p>";
            $profile->save();

            $user->id == 1 ? $user->assignRole('freelancer') : $user->assignRole('hirer');
        }
    }
}
