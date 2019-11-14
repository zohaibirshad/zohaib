<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Profile;
use App\Models\Bookmark;
use App\Models\Country;
use App\Models\Invite;
use App\Models\Job;
use App\Models\Milestone;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FreelancersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('freelancers.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function freelancers(Request $request)
    {

        $keyword = $request->title;
        $skills = $request->skills;
        $sort = $request->sort;
        $country = $request->country;
        $minHourlyRate = $request->min_hourly_rate;
        $maxHourlyRate = $request->max_hourly_rate;


        $freelancer = Profile::where('type', 'freelancer')->with('skills', 'country', 'reviews')
            ->when(!empty($keyword), function ($query) use ($keyword) { 
                $query->where('name', 'LIKE', "%$keyword%");
            })
            ->when(!empty($skills), function ($query) use ($skills) {
                $query->whereHas("skills", function ($query) use ($skills) {
                    if (!is_array($skills)) {
                        $query->where("id", $skills);
                    } else {
                        $query->whereIn("id", $skills);
                    }
                });
            })
            ->when(!empty($country), function ($query) use ($country) {
                $query->whereHas("country", function ($query) use ($country) {
                    $query->where("id", $country);
                });
            })
            ->when(!empty($minHourlyRate) && !empty($maxHourlyRate), function ($query) use ($minHourlyRate, $maxHourlyRate) {
                $query->whereBetween('rate', [$minHourlyRate, $maxHourlyRate]);
            })
            ->when(!empty($sort), function ($query) use ($sort) {

                if ($sort == 'high_hourly_rate') {
                    return $query->orderBy('rate', 'desc');
                }
                if ($sort == 'low_hourly_rate') {
                    return $query->orderBy('rate', 'asc');
                }
            })->when(empty($sort), function ($query) {
                $query->latest();
            })
            ->paginate(20);



        return response()->json($freelancer);
    }

    public function countries()
    {
        $countries = Country::get();

        return response()->json($countries);
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


    /**
     * Bookmark Job.
     *
     * @param  string  $job_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookmark_job($job_uuid)
    {
        $job = Job::where('uuid', $job_uuid)->first();
        $bookmark = new Bookmark;
        $bookmark->user_id = Auth::user()->id;
        $bookmark->job_id = $job->id;
        $bookmark->save();

        return response()->json([
            'status' => 'Bookmarked',
            'message' => "Bookmarked Successfully"
        ]);
    }

    /**
     * Delete Bookmark Job.
     *
     * @param  string  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete_bookmark_job($id)
    {
        $bookmark = Bookmark::find($id);
        $bookmark->destroy($id);

        return response()->json([
            'status' => 'Bookmarke Deleted',
            'message' => "Bookmarked Successfully Deleted"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {

        $freelancer = Profile::with('skills', 'country', 'reviews', 'jobs_completion', 'social_links')
        ->where('uuid', $uuid)->first();

        $jobs = Job::where('status', 'completed')->where('profile_id', $freelancer->id)->paginate(5);

        if (empty($freelancer)) {
            abort(404);
        }

        $isBookmakedByUser = Bookmark::where(['profile_id' => $freelancer->id, 'user_id' => Auth::id()])->exists();

        return view('freelancers.show', compact('freelancer', 'jobs', 'isBookmakedByUser'));
    }



    /**
     * Review a hirer.
     *
     * @param  string  $job_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function review_hirer(Request $request, $profile_uuid)
    {
        $validateData = $request->validate([
            'rating' => 'required',
            'body' => 'nullable',
        ]);

        $profile = Profile::where('uuid', $profile_uuid)->first();

        $profile->reviews()->create([
            'user_id' => Auth::user()->id,
            'rating' => $request->rating,
            'body' => $request->body
        ]);

        return response()->json([
            'status' => "Success",
            'message' => "Review was saved successfully"
        ]);
    }

    /**
     * Review a Job.
     *
     * @param  string  $job_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function review_job(Request $request, $job_uuid)
    {
        $validateData = $request->validate([
            'rating' => 'required',
            'body' => 'nullable',
        ]);

        $job = Job::where('uuid', $job_uuid)->first();

        $job->reviews()->create([
            'user_id' => Auth::user()->id,
            'rating' => $request->rating,
            'body' => $request->body
        ]);

        return redirect()->back()->with('success', 'Review Successful');

        // return response()->json([
        //     'status' => "Success",
        //     'message' => "Review was saved successfully"
        // ]);
    }

    public function edit_job_review(Request $request, $id)
    {
        $validateData = $request->validate([
            'rating' => 'required',
            'body' => 'nullable',
        ]);

        $review = Review::find($id);
        $review->rating = $request->rating;
        $review->body = $request->body;
        $review->save();

        return redirect()->back()->with('success', 'Review updated');
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
     * Reject Invite.
     *
     * @param  string  $invite_uuid
     * @return \Illuminate\Http\Response
     */
    public function reject_invite($invite_uuid)
    {
        $invite = Invite::where('uuid', $invite_uuid)->first();

        $profile = Profile::where('user_id', Auth::user()->id)->first();

        $invite->status = 'rejected';
        $invite->profile_id = $profile->id;
        $invite->destroy( $invite->id);

        return response()->json([
            'status' => "Success",
            'message' => "Invite was rejected successfully"
        ]);
    }


    /**
     * View all Invites.
     *
     * @return \Illuminate\Http\Response
     */
    public function bids()
    {
        $profile = Profile::where('user_id', Auth::user()->id)->first();

        $bids = Bid::where('profile_id', $profile->id)
            ->with('job', 'profile')
            ->get();

        return view('dashboard.my_bids', compact('bids'));
    }

    /**
     * Make a bid on a job
     *
     * @param  string  $job_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function make_bid(Request $request, $job_uuid)
    {
        $validateData = $request->validate([
            'rate' => 'required',
            'delivery_time' => 'required',
            'delivery_type' => 'required',
        ]);

        $profile = Profile::where('user_id', Auth::user()->id)->first();
        $job = Job::where('uuid', $job_uuid)->first();

        $bid = new Bid;
        $bid->profile_id = $profile->id;
        $bid->job_id = $job->id;
        $bid->rate = $request->rate;
        $bid->delivery_type = $request->delivery_type;
        $bid->delivery_time = $request->delivery_time;
        $bid->description = $request->description;
        $bid->status = 'pending';
        $bid->save();

        return redirect()->back()->with('success', 'Bid Placed Successfully!');

        // return response()->json([
        //     'status' => "Success",
        //     'message' => "Bid was saved successfully"
        // ]);
    }

    /**
     * Edit Bid.
     *
     * @param  string  $bid_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit_bid(Request $request, $bid_uuid)
    {
        $validateData = $request->validate([
            'rate' => 'required',
            'delivery_time' => 'required',
            'delivery_type' => 'required',
        ]);


        $bid = Bid::find($request->id);
        $bid->rate = $request->rate;
        $bid->delivery_type = $request->delivery_type;
        $bid->delivery_time = $request->delivery_time;
        $bid->save();

        return redirect()->back()->with('success', 'Bid Updated Successfully!');

        // return response()->json([
        //     'status' => "Success",
        //     'message' => "Bid was updated successfully"
        // ]);
    }


    /**
     * Delete Bid.
     *
     * @param  string  $bid_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete_bid(Request $request, $bid_uuid)
    {
        $bid = Bid::where('uuid', $bid_uuid)->first();
        $bid->destroy($bid->id);

        return redirect()->back()->with('success', 'Bid Deleted Successfully!');

        // return response()->json([
        //     'status' => "Success",
        //     'message' => "Bid was deleted successfully"
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
     * Add a milestone
     *
     * @param  string  $job_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_milestone(Request $request, $job_uuid)
    {
        $validateData = $request->validate([
            'heading' => 'required',
            'activity' => 'required',
            'cost' => 'required',
            'status' => 'required',
        ]);

        $job = Job::where('uuid', $job_uuid)->first();

        $milestone = new Milestone;
        $milestone->job_id = $job->id;
        $milestone->heading = $request->heading;
        $milestone->activity = $request->activity;
        $milestone->cost = $request->cost;
        $milestone->status = $request->status;
        $milestone->save();

        return response()->json([
            'status' => "Success",
            'message' => "Milestone was saved successfully"
        ]);
    }

    /**
     * Edit a milestone
     *
     * @param  string  $mile_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit_milestone(Request $request, $mile_uuid)
    {
        $validateData = $request->validate([
            'heading' => 'required',
            'activity' => 'required',
            'cost' => 'required',
            'status' => 'required',
        ]);

        $milestone = Milestone::where('uuid', $mile_uuid);
        $milestone->heading = $request->heading;
        $milestone->activity = $request->activity;
        $milestone->cost = $request->cost;
        $milestone->status = $request->status;
        $milestone->save();

        return response()->json([
            'status' => "Success",
            'message' => "Milestone was Updated successfully"
        ]);
    }


    /**
     * Delete a milestone
     *
     * @param  string  $mile_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete_milestone(Request $request, $mile_uuid)
    {
        $milestone = Milestone::where('uuid', $mile_uuid)->first();
        $milestone->destroy($milestone->id);

        return response()->json([
            'status' => "Success",
            'message' => "Milestone was deleted successfully"
        ]);
    }
}
