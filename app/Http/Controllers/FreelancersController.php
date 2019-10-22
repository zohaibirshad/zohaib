<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

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

        if($request->has('search')){
            $keyWord = $request->title;
            $industry = $request->industry;
            $skills = $request->skills;
            $sort = $request->sort;
            $city = $request->city;

            $jobs = Profile::with('industry', 'skills', 'country', 'reviews', 'jobs', 'jobs_completion')
            ->when(!empty($keyWord), function ($query) use ($keyWord) {
                $query->where('name', 'LIKE', "%$keyWord%");
            })
            ->when(!empty($city), function ($query) use ($city) {
                $query->where('city','LIKE', "%$city%");
            })
            ->when(!empty($skills), function ($query) use ($skills) {
                $query->whereHas("skills", function($query) use ($skills) {
                    if(!is_array($skills)){
                        $query->where("id", $skills);
                    }
                    $query->whereIn("id", $skills);
                });
            })
            ->when(!empty($industry), function ($query) use ($industry) {
                $query->whereHas("industry", function($query) use ($industry) {
                    $query->whereIn("id", $industry);
                });
            })
            ->when(!empty($country), function ($query) use ($country) {
                $query->whereHas("country", function($query) use ($country) {
                    $query->whereIn("id", $country);
                });
            })
            ->when(!empty($from_hour_price), function ($query) use ($from_hour_price) {
                    $query->where('rate', '>=', $from_hour_price);
            })
            ->when(!empty($to_hour_price), function ($query) use ($to_hour_price) {
                    $query->where('rate', '<=', $to_hour_price);
            })
            ->when(!empty($sort), function ($query) use ($sort) {

                if($sort == 'high_hourly_rate'){
                    return $query->orderBy('rate', 'desc');
                }
                if($sort == 'low_hourly_rate'){
                    return $query->orderBy('rate', 'asc');
                }
               
            })->when(empty($sort), function ($query)  {
                    $query->latest();
            })
            ->paginate(20);
        }else{
           $jobs = Profile::with('industry', 'skills', 'country', 'reviews', 'jobs', 'jobs_completion')
                                ->latest()
                                ->paginate(20);
        }
        
        return response()->json($jobs);
    }

    
    
    /**
     * Bookmark Job.
     *
     * @param  string  $job_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookmark_job(Request $request, $job_uuid)
    {
        //
    }

     /**
     * Delete Bookmark Job.
     *
     * @param  string  $job_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete_bookmark_job(Request $request, $job_uuid)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('freelancers.show');
    }

    /**
     * Review a hirer.
     *
     * @param  string  $job_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function review_hirer(Request $request, $job_uuid)
    {
        //
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
        //
    }

    /**
     * Accept Invite.
     *
     * @param  string  $invite_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accept_invite(Request $request, $invite_uuid)
    {
        //
    }

    /**
     * Reject Invite.
     *
     * @param  string  $invite_uuid
     * @return \Illuminate\Http\Response
     */
    public function reject_invite($invite_uuid)
    {
        //
    }

    /**
     * Make a bid on a job
     *
     * @param  string  $job_uuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bid(Request $request, $job_uuid)
    {
        //
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
        //
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
        //
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
        //
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
        //
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
        //
    }
}
