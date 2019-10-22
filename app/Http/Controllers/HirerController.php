<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

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
        //
    }

     /**
     * recent not assigned jobs jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function not_assigned_jobs()
    {
        $jobs = Job::where('status', 'not assigned')
                    ->with('bids')
                    ->withCount('bids')
                    ->get();

        return view('dashboard.jobs.new_jobs', compact('jobs'));
    }

     /**
     * recent assigned jobs jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assigned_jobs()
    {
        $jobs = Job::where('status', 'assigned')
                    ->with('milestones', 'accepted_bid')
                    ->withCount('milestones')
                    ->get();

        return view('dashboard.jobs.ongoing_jobs', compact('jobs'));
    }

    /**
     * recent assigned jobs jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function completed_jobs()
    {
        $jobs = Job::where('status', 'completed')
                    ->with('milestones', 'accepted_bid', 'payments')
                    ->withCount('milestones')
                    ->get();

        return view('dashboard.jobs.completed_jobs', compact('jobs'));
    }

     /**
     * manage bids.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $job_uuid
     * @return \Illuminate\Http\Response
     */
    public function manage_bids(Request $request, $job_uuid)
    {
        $job = Job::where('uuid', $job_uuid)->first();
        $bids = Bid::where('job_id', $job->id)->with('profile')->get();

        return view('dashboard.jobs.bidders', compact('bids'));
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
            'profile_id' => 'required'
        ]);
        $bid = Bid::where('uuid', $bid_uuid)->first();
        $bid->status = 'accepted';
        $bid->save();

        $job = Job::where('id', $bid->id)->first();
        $job->status = 'assigned';
        $job->profile_id = $request->profile_id;
        $job->save();

        return response()->json([
            'status' => "Assigned",
            'message' => "Job was assigned successfully"
        ]);
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

        return response()->json([
            'status' => "Changed",
            'message' => "Milestone Status was changed successfully"
        ]);
    }
}
