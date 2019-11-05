<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $profile_uuid
     * @return \Illuminate\Http\Response
     */
    public function update_account(Request $request)
    {
        $id = Auth::User()->id;
        
        $messages = [
            'password.regex' => 'Password must contain at least one number and both uppercase and lowercase letters.'
        ];

        $validateData = Validator::make($request->all(), [
             'email' =>  ['nullable', 'email',
                                Rule::unique('users', 'email')->ignore($id),
                            ],
            'old_password' => 'nullable|string|min:6',
            'password' => 'nullable|min:6|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
        ], $messages)->validate();
      

        $current_password = Auth::User()->password;           
        if(Hash::check($request->old_password, $current_password))
        {           
            $user_id = Auth::User()->id;                       
            $obj_user = User::find($user_id);
            $obj_user->password = Hash::make($request->password);
            if($request->has('email')){
                $obj_user->email = $request->email;
            }
            $obj_user->save(); 
            return redirect()->back()->with('status', 'User Account successfully updated');
        }
        else
        {   
            if($request->filled('email'))
            {
                $user = Auth::User();  
                $user->email = $request->email;     
                $user->save();                 
                        
                return redirect()->back()->with('status', 'Email was successfully updated');            
            }

            return redirect()->back()->with('error', 'Current Password was incorrect');            
        }
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $profile_uuid
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request, $profile_uuid)
    {
        $validateData = $request->validate([
            'profile_name' => 'required|string',
            'profile_email' => 'required|unique:companies,email',
            'profile_phone' => 'required|numeric',
            'address' => 'nullable|string',
            'headline' => 'nullable|string',
            'city' => 'nullable|string',
            'region' => 'nullable|string',
            'country' => 'nullable|string',
            'rate' => 'nullable|string',
            'skills' => 'nullable',
            'picture' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'documents' => 'nullable|file'
        ]);

        $profile = Profile::where('user_id', Auth::user()->id)->first();
        $profile->name = $request->profile_name;
        $profile->email = $request->profile_email;
        $profile->phone1 = $request->profile_phone;
        $profile->address = $request->address;
        $profile->city = $request->city;
        $profile->region = $request->region;
        $profile->country = $request->country;
        $profile->rate = $request->rate;
        $profile->headline = $request->headline;
        $profile->save();

        $profile->skills()->attach($request->skills);


        if ($request->hasFile('picture')) {
                $profile
                ->addMediaFromRequest('picture') //starting method
                ->toMediaCollection('profile'); 
        }  

        if ($request->hasFile('documents')) {
            $fileAdders = $profile
                ->addMultipleMediaFromRequest($request->file('document'))
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('project_files');
            });
        }

      return response()->back()->with('status', "profile updated Succesfully");

    }
}
