<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\JobsController;

class ApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application home.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(JobsController $jobs)
    {
        $recent_jobs = $jobs->recent_featured_jobs(6);

        $job_categories = $jobs->job_categories(8);
        
        

        return view('home', compact('recent_jobs', 'job_categories'));
    }
}
