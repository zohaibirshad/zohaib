<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Job;
use App\Models\Invite;
use App\Models\Milestone;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HirerController extends Controller
{
    /**
     * post a job
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post_job(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'industry_id' => 'required',
            'country_id' => 'required',
            'job_budget_id' => 'nullable',
            'max_budget' => 'nullable',
            'min_budget'=> 'nullable',
            'budget_type' => 'nullable',
            'skills' => 'required',
            'description' => 'required',
            'documents' => 'nullable'
        ]);

        $job = new Job;
        $job->title = $request->title;
        $job->country_id = $request->country_id;
        $job->max_budget = $request->max_budget;
        $job->min_budget = $request->min_budget;
        $job->budget_type = $request->budget_type;
        $job->description = $request->description;
        $job->industry_id = $request->industry_id;
        $job->save();

        $job->skills()->sync($request->skills);

        if ($request->hasFile('documents')) {
            $fileAdders = $job
                ->addMultipleMediaFromRequest($request->file('documents'))
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('project_files');
            });
        }
        
        return response('new-jobs')->with('status', "Job created Succesfully");

    }

     /**
     * recent not assigned jobs jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function not_assigned_jobs()
    {
        $jobs = Job::where('user_id', Auth::user()->id)
                    ->where('status', 'not assigned')
                    ->with('bids', 'milestones')
                    ->withCount('bids', 'milestones')
                    ->get();

        return view('dashboard.jobs.new_jobs', compact('jobs'));
    }


     /**
     * manage bids for a job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $job_uuid
     * @return \Illuminate\Http\Response
     */
    public function manage_bids(Request $request, $job_uuid)
    {
        $job = Job::where('uuid', $job_uuid)->first();
        $bids = Bid::where('job_id', $job->id)->with('profile')->get();

        return view('dashboard.bidders', compact('bids', 'job'));
    }
    


     /**
     * accept bids.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $bid_uuid
     * @return \Illuminate\Http\Response
     */
    public function accept_bid(Request $request, $bid_uuid)
    {
        $validateData = $request->validate([
            'profile_id' => 'required',
        ]);
        $bid = Bid::where('uuid', $bid_uuid)->first();
        $bid->status = 'accepted';
        $bid->save();

        $job = Job::where('id', $bid->job_id)->first();
        $job->status = 'assigned';
        $job->profile_id = $request->profile_id;
        $job->save();

        return back()->with('success', 'Bid Accepted Successfully');

        // return response()->json([
        //     'status' => "Success",
        //     'message' => "Job was assigned successfully"
        // ]);
    }

    /**
     * view milestones.
     *
     * @param  string  $job_uuid
     * @return \Illuminate\Http\Response
     */
    public function milestones($job_uuid)
    {

        $job = Job::where('uuid', $job_uuid)->with('milestones')->first();

        return view('dashboard.milestones', compact('job'));
    }


    /**
     * update milestone status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $mile_uuid
     * @return \Illuminate\Http\Response
     */
    public function update_milestone(Request $request, $mile_uuid)
    {
        $validateData = $request->validate([
            'status' => 'required'
        ]);

        $milestone = Milestone::where('uuid', $mile_uuid)->first();
        $milestone->status = $request->status;
        $milestone->save();

        return back()->with('success', 'Payment Released For Milestone');

        // return response()->json([
        //     'status' => "Success",
        //     'message' => "Milestone Status was changed successfully"
        // ]);
    }

    public function release_payment_for_milestone(Request $request, $mile_uuid)
    {

        $milestone = Milestone::where('uuid', $mile_uuid)->first();
        $milestone->is_paid = 1;
        $milestone->save();

        // Actually add the money to use account

        return back()->with('success', 'Payment Released For Milestone');
    }


    /**
     * Review a freelancer.
     *
     * @param  string  $profile_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function review_freelancer(Request $request, $profile_uuid)
    {
        $validateData = $request->validate([
            'rating' => 'required',
            'body' => 'nullable',
        ]);

        $profile = Profile::where('uuid', $profile_uuid)->first();

        $job = Job::find($request->job_id);
        $job->ontime = $request->ontime;
        $job->onbudget = $request->onbudget;
        $job->save();

        $profile->reviews()->create([
            'user_id' => Auth::user()->id,
            'rating' => $request->rating,
            'body' => $request->body,
            'job_id' => $request->job_id,
        ]);

        return back()->with('success', 'Review Successful');

        // return response()->json([
        //     'status' => "Success",
        //     'message' => "Review was saved successfully"
        // ]);


    }


    /**
     * View All Reviews.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews()
    {

        $reviews = Review::where('user_id', Auth::user()->id)->get();

        return view('dashboard.reviews',  compact('reviews'));


    }

      /**
     * Send Invite.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_invite(Request $request)
    {
        $validateData = $request->validate([
            'profile' => 'nullable',
            'job' => 'required',
            'message' => 'nullable',
            'documents.*' => 'file|nullable',
        ]);

        $invite = new Invite;
        $invite->job_id = $request->job;
        $invite->user_id = Auth::user()->id;
        $invite->profile_id = $request->profile;
        $invite->message = $request->message;
        $invite->status = 'pending';
        $invite->save();

        if ($request->hasFile('documents')) {
            $fileAdders = $invite
                ->addMultipleMediaFromRequest(['documents'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('project_files');
            });
        }
       

        return redirect()->back()->with('status', "Invite successfully sent");

    }

    /**
     * View all Invites.
     *
     * @return \Illuminate\Http\Response
     */
    public function invites()
    {
        $invites = Invite::where('user_id', Auth::user()->id)
                    ->with('job', 'profile')
                    ->get();

        return view('dashboard.jobs.invites', compact('invites'));
    }

     /**
     * Bookmark freelancer.
     *
     * @param  string  $profile_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookmark_freelancer($profile_uuid)
    {
        $profile = Profile::where('uuid', $profile_uuid)->first();
        $bookmark = new Bookmark;
        $bookmark->user_id = Auth::user()->id;
        $bookmark->profile_id = $profile->id;
        $bookmark->save();

        return response()->json([
            'status' => 'Success',
            'message' => "Bookmarked Successfully"
        ]);
    }


     /**
     * View Bookmarks.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookmarks()
    {
        $bookmarks = Bookmark::where('user_id', Auth::user()->id)->get();

        return view('dashboard.bookmarks', compact('bookmarks'));
    }
}
