<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Country;
use App\Models\Invite;
use App\Models\Job;
use App\Models\Milestone;
use App\Models\Profile;
use App\Models\Skill;
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
        $user = Profile::where('user_id', Auth::id())->with('media')->first();
        $countries = Country::get();
        $skills = Skill::orderBy('title', 'asc')->get();

        // return $user;

        return view('dashboard.settings', compact('countries', 'user', 'skills'));
    }
    
    public function invites()
    {
        $user = Auth::user();
        $freelancer = Profile::where('user_id', Auth::id())->first();

        $invites = Invite::where('user_id', $user->id)
        ->orwhere('profile_id', $freelancer->id)
            ->with('job', 'profile')
            ->get();

        return view('dashboard.jobs.invites', compact('invites'));
    }

    public function milestones(Request $request, $slug)
    {
        $user = Auth::user();
        $freelancer = Profile::where('user_id', Auth::id())->first();

        $job = Job::where('slug', $slug)->first();

        if(empty($job)){
            abort(404);
        }

        if($user->hasRole('hirer')){
            $m = Milestone::where('job_id', $job->id)
            ->with('profile')
            ->where('user_id', $user->id);

            $done = Milestone::where('job_id', $job->id)
            ->where('status', 'done')
            ->where('user_id', $user->id)
            ->count();
        } else {
            $m = Milestone::where('job_id', $job->id)
            ->with('profile')
            ->where('profile_id', $freelancer->id);

            $done = Milestone::where('job_id', $job->id)
            ->where('status', 'done')
            ->where('profile_id', $freelancer->id)
            ->count();
        }


        $milestones = $m->get();

        $completion = ($done / $m->count()) * 100;

        return view('dashboard.milestones', compact('milestones', 'job', 'completion'));
    }

    public function bidders(Request $request, $slug)
    {
        $user = Auth::user();
        $job = Job::where('slug', $slug)->first();

        if(empty($job)){
            abort(404);
        }

        return view('dashboard.bidders', compact('job'));
    }
}
