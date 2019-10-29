<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Job;
use App\Models\Milestone;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.dashboard');
    }

    public function settings(Request $request)
    {
        $user = Auth::user();
        $countries = Country::get();

        return view('dashboard.settings', compact('countries', 'user'));
    }

    public function milestones(Request $request, $slug)
    {
        $user = Auth::user();
        $freelancer = Profile::where('user_id', Auth::id())->first();

        $job = Job::where('slug', $slug)->first();

        if(empty($job)){
            abort(404);
        }

        $milestones =  Milestone::where('job_id', $job->id)
        ->where('user_id', $user->id)
        ->orwhere('profile_id', $freelancer->id)
        ->get();

        return view('dashboard.milestones', compact('milestones', 'job'));
    }
}
