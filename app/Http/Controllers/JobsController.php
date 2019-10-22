<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Job;
use App\Models\User;
use App\Models\Skill;
use App\Models\Country;
use App\Models\Industry;
use App\Models\JobBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $totals_jobs = DB::table('jobs')
                    ->selectRaw('count(*) as total')
                    ->selectRaw("count(case when status = 'completed' then 1 end) as completed")
                    ->first();

        $total_freelancers = User::role('freelancer')->count();

        return view('jobs.index', compact('totals_jobs', 'total_freelancers'));
    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function jobs(Request $request)
    {

        if($request->has('search')){
            $keyWord = $request->title;
            $industry = $request->industry;
            $location = $request->location;
            $to_fixed_price = $request->to_fixed_price;
            $from_fixed_price = $request->from_fixed_price;
            $to_hour_price = $request->to_hour_price;
            $from_hour_price = $request->from_hour_price;
            $skills = $request->skills;
            $sort = $request->sort;
            $city = $request->city;

            $jobs = Job::with('industry', 'skills', 'job_budget', 'country')
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
            ->when(!empty($from_fixed_price), function ($query) use ($from_fixed_price) {
                $query->whereHas("job_budget", function($query) use ($from_fixed_price) {
                    $query->where('to', '>=', $from_fixed_price);
                    $query->where('status', 'fixed');
                });
            })
            ->when(!empty($to_fixed_price), function ($query) use ($to_fixed_price) {
                $query->whereHas("job_budget", function($query) use ($to_fixed_price) {
                    $query->where('from', '<=', $to_fixed_price);
                    $query->where('status', 'fixed');
                });
            })
            ->when(!empty($from_hour_price), function ($query) use ($from_hour_price) {
                $query->whereHas("job_budget", function($query) use ($from_hour_price) {
                    $query->where('to', '>=', $from_hour_price);
                    $query->where('status', 'hour');
                });
            })
            ->when(!empty($to_hour_price), function ($query) use ($to_hour_price) {
                $query->whereHas("job_budget", function($query) use ($to_hour_price) {
                    $query->where('from', '<=', $to_hour_price);
                    $query->where('status', 'hour');
                });
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if($sort == 'featured'){
                    $query->orwhere('featured', 'yes');
                }
                if($sort == 'highest_fixed_budget'){
                    $query->orderByDesc(
                            JobBudget::select('to', 'status')
                                ->whereColumn('job_id', 'job.id')
                                ->where('status', 'fixed')
                                ->orderBy('to', 'desc')
                                ->limit(1)
                    );
                }
                if($sort == 'lowest_fixed_budget'){
                    $query->orderByDesc(
                            JobBudget::select('to', 'status')
                                ->whereColumn('job_id', 'job.id')
                                ->where('status', 'fixed')
                                ->orderBy('to', 'asc')
                                ->limit(1)
                    );
                }
                if($sort == 'lowest_hour_budget'){
                    $query->orderByDesc(
                            JobBudget::select('to', 'status')
                                ->whereColumn('job_id', 'job.id')
                                ->where('status', 'hour')
                                ->orderBy('to', 'asc')
                                ->limit(1)
                    );
                }
                if($sort == 'highest_hour_budget'){
                    $query->orderByDesc(
                            JobBudget::select('to', 'status')
                                ->whereColumn('job_id', 'job.id')
                                ->where('status', 'fixed')
                                ->orderBy('to', 'desc')
                                ->limit(1)
                    );
                }

                if($sort == 'highest_number_of_bids'){
                     $query->withCount('bids');
                     $query->orderByDesc('bids_count');
                }
                if($sort == 'lowest_number_of_bids'){
                    $query->withCount('bids');
                    $query->orderByAsc('bids_count');
               }
            })->when(empty($sort), function ($query)  {
                    $query->latest();
            })
            ->where('status', 'not assigned')
            ->paginate(20);
        }else{
           $jobs = Job::with('industry', 'skills', 'job_budget', 'country')
                                ->where('status', 'not assigned')
                                ->latest()
                                ->paginate(20);
        }
        
        return response()->json($jobs);
    }

     /**
     * Get all Industries.
     *
     * @return \Illuminate\Http\Response
     */
    public function industries($limit = NULL)
    {
        $industries = $this->job_categories($limit);

        return response()->json($industries);
    }

     /**
     * Get all Skills.
     *
     * @return \Illuminate\Http\Response
     */
    public function skills($limit = NULL)
    {
        $skills = $this->job_skills($limit);

        return response()->json($skills);
    }

     /**
     * Get all Budgets.
     *
     * @return \Illuminate\Http\Response
     */
    public function budgets()
    {
        $max_fixed_budget = JobBudget::where('type', 'fixed')->max('to');
        $min_fixed_budget = JobBudget::where('type', 'fixed')->min('from');
        $max_hour_budget = JobBudget::where('type', 'hour')->max('to');
        $min_hour_budget = JobBudget::where('type', 'hour')->max('from');

        return response()->json([
            'max_fixed_budget' => $max_fixed_budget ,
            'min_fixed_budget' => $min_fixed_budget ,
            'max_hour_budget' => $max_hour_budget ,
            'min_hour_budget' => $min_hour_budget ,
        ]);
    }


    /**
     * Get all Cities.
     *
     * @return \Illuminate\Http\Response
     */
    public function cities()
    {
        $countries = Country::withCount('jobs')->get();

        return response()->json($countries);
    }


    /**
     * Get all Countires.
     *
     * @return \Illuminate\Http\Response
     */
    public function countries()
    {
        $countries = Country::withCount('jobs')->get();

        return response()->json($countries);
    }


     /**
     * Get recent jobs.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function recent($limit = NULL)
    {

        if($limit != NULL)
        {
            $recent_jobs = Job::with('industry', 'skills', 'job_budget', 'country', 'attachments')->latest()->limit($limit)->get();
        }
        elseif($limit == NULL)
        {
            $recent_jobs = Job::with('industry', 'skills', 'job_budget', 'country', 'attachments')->latest()->get();

        }
        
        return $recent_jobs;
    }

    /**
     * Get recent featured jobs.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function recent_featured_jobs($limit = NULL)
    {
        if($limit != NULL)
        {
            $recent_jobs = Job::where('featured', 'yes')->with('industry', 'skills', 'job_budget', 'country', 'attachments')->latest()->limit($limit)->get();
        }
        elseif($limit == NULL)
        {
            $recent_jobs = Job::where('featured', 'yes')->with('industry', 'skills', 'job_budget', 'country', 'attachments')->latest()->get();

        }
        
        return $recent_jobs;

    }

    /**
     *Get recent featured job categories.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function job_categories($limit = NULL)
    {
        if($limit != NULL)
        {
            $job_categories = Industry::where('featured', 'yes')->with('media')->latest()->limit($limit)->withCount('jobs')->get();
        }
        elseif($limit == NULL)
        {
            $job_categories = Industry::where('featured', 'yes')->with('media')->latest()->withCount('jobs')->get();

        }

        return $job_categories;
    }

    /**
     *Get recent featured job Skills.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function job_skills($limit = NULL)
    {
        if($limit != NULL)
        {
            $job_skills = Skill::latest()->limit($limit)->withCount('jobs')->get();
        }
        elseif($limit == NULL)
        {
            $job_skills = Skill::latest()->withCount('jobs')->get();

        }

        return $job_skills;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::where('id', $id)
                ->with(['industry', 'skills', 'job_budget', 'country', 'attachments', 'bids'])
                ->first();

        $bids = Bid::where('job_id', $id)
                ->with('profile')
                ->get();

        return view('jobs.show', compact('job', 'bids'));
    }


}
