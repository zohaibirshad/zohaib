<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Bookmark;
use App\Models\Job;
use App\Models\Skill;
use App\Models\Country;
use App\Models\Industry;
use App\Models\JobBudget;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('jobs.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function jobs(Request $request)
    {

        $keyWord = $request->title;
        $industry = $request->industry;
        $location = $request->location;
        $max_fixed_price = $request->max_fixed_price;
        $min_fixed_price = $request->min_fixed_price;
        $max_hour_price = $request->max_hour_price;
        $min_hour_price = $request->min_hour_price;
        $skills = $request->skills;
        $sort = $request->sort;
        $city = $request->city;
        $country = $request->country;
        $budgetType = $request->budget_type;
        $minBudget = $request->min_budget;
        $maxBudget = $request->max_budget;


        $jobs = Job::with('industry', 'skills', 'job_budget', 'country')
            ->when(!empty($keyWord), function ($query) use ($keyWord) {
                $query->where('title', 'LIKE', "%$keyWord%");
            })
            ->when(!empty($city), function ($query) use ($city) {
                $query->where('city', 'LIKE', "%$city%");
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
            ->when(!empty($industry), function ($query) use ($industry) {
                $query->whereHas("industry", function ($query) use ($industry) {
                    if (!is_array($industry)) {
                        $query->where("id", $industry);
                    } else {
                        $query->whereIn("id", $industry);
                    }
                });
            })
            ->when(!empty($country), function ($query) use ($country) {
                $query->whereHas("country", function ($query) use ($country) {
                    $query->whereIn("id", $country);
                });
            })
            ->when(!empty($budgetType), function ($query) use ($budgetType) {
                $query->where('budget_type', $budgetType);
            })
            ->when(!empty($minBudget) && !empty($maxBudget), function ($query) use ($minBudget, $maxBudget) {
                $query->where('max_budget', '>', $minBudget)
                    ->where('min_budget', '<', $maxBudget);
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if ($sort == 'featured') {
                    $query->orwhere('featured', 'yes');
                }
                if ($sort == 'most_bids') {
                    $query->withCount('bids');
                    $query->orderByDesc('bids_count');
                }
                if ($sort == 'fewest_bids') {
                    $query->withCount('bids');
                    $query->orderByAsc('bids_count');
                }
                if ($sort == 'newest') {
                    $query->latest();
                }
                if ($sort == 'oldest') {
                    $query->oldest();
                }
                if ($sort == 'lowest_price') {
                    $query->orderBy('min_budget', 'asc');
                }
                if ($sort == 'highest_price') {
                    $query->orderBy('max_budget', 'asc');
                }
            })->when(empty($sort), function ($query) {
                $query->latest();
            })
            ->where('status', 'not assigned')
            ->paginate(20);


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
            'max_fixed_budget' => $max_fixed_budget,
            'min_fixed_budget' => $min_fixed_budget,
            'max_hour_budget' => $max_hour_budget,
            'min_hour_budget' => $min_hour_budget,
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

        if ($limit != NULL) {
            $recent_jobs = Job::with('industry', 'skills', 'job_budget', 'country')->latest()->limit($limit)->get();
        } elseif ($limit == NULL) {
            $recent_jobs = Job::with('industry', 'skills', 'job_budget', 'country')->latest()->get();
        }

        return $recent_jobs;
    }

    /**
     * Get completed jobs.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function completed_jobs($limit = NULL)
    {

        if ($limit != NULL) {
            $completed_jobs = Job::where('status', 'completed')
                ->with('industry', 'skills', 'job_budget', 'country')->latest()->limit($limit)->get();
        } elseif ($limit == NULL) {
            $completed_jobs = Job::where('status', 'completed')
                ->with('industry', 'skills', 'job_budget', 'country')->latest()->get();
        }

        return $completed_jobs;
    }

    /**
     * Get recent featured jobs.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function recent_featured_jobs($limit = NULL)
    {
        if ($limit != NULL) {
            $recent_jobs = Job::where('featured', 'yes')->with('industry', 'skills', 'job_budget', 'country')->latest()->limit($limit)->get();
        } elseif ($limit == NULL) {
            $recent_jobs = Job::where('featured', 'yes')->with('industry', 'skills', 'job_budget', 'country')->latest()->get();
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
        if ($limit != NULL) {
            $job_categories = Industry::where('featured', 'yes')->orderBy('title', 'asc')->with('media')->latest()->limit($limit)->withCount('jobs')->get();
        } elseif ($limit == NULL) {
            $job_categories = Industry::where('featured', 'yes')->orderBy('title', 'asc')->with('media')->latest()->withCount('jobs')->get();
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
        if ($limit != NULL) {
            $job_skills = Skill::orderBy("title", "asc")->limit($limit)->withCount('jobs')->get();
        } elseif ($limit == NULL) {
            $job_skills = Skill::orderBy("title", "asc")->withCount('jobs')->get();
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
        $job = Job::where('slug', $id)
            ->with(['owner', 'industry', 'skills', 'job_budget', 'country', 'bids'])
            ->first();

        $bids = Bid::where('job_id', $id)
            ->with('profile')
            ->get();

        if (empty($job)) {
            abort(404);
        }

        $isBookmakedByUser = Bookmark::where(['job_id' => $job->id, 'user_id' => Auth::id()])->exists();

        return view('jobs.show', compact('job', 'bids', 'isBookmakedByUser'));
    }

    public function ongoing_jobs()
    {
        $freelancer = Profile::where('user_id', Auth::user()->id)->first();
        $user = Auth::user();

        if ($user->hasRole('hirer')) {
            $jobs = Job::where('user_id',  $user->id)
                ->where('status', 'assigned')
                ->with('milestones', 'accepted_bid', 'job_budget')
                ->withCount('milestones')
                ->get();
        } else {
            $jobs = Job::where('profile_id',  $freelancer->id)
                ->where('status', 'assigned')
                ->with('milestones', 'accepted_bid', 'job_budget')
                ->withCount('milestones')
                ->get();
        }

        return view('dashboard.jobs.ongoing_jobs', compact('jobs'));
    }
    public function completed_jobs_()
    {
        $freelancer = Profile::where('user_id', Auth::user()->id)->first();
        $user = Auth::user();

        if ($user->hasRole('hirer')) {
            $jobs = Job::where('user_id',  $user->id)
                ->where('status', 'completed')
                ->with('milestones', 'accepted_bid', 'job_budget')
                ->withCount('milestones')
                ->get();
        } else {
            $jobs = Job::where('profile_id',  $freelancer->id)
                ->where('status', 'completed')
                ->with('milestones', 'accepted_bid', 'job_budget')
                ->withCount('milestones')
                ->get();
        }

        return view('dashboard.jobs.completed_jobs', compact('jobs'));
    }
}
