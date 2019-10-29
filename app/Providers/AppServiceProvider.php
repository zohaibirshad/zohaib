<?php

namespace App\Providers;

use App\Models\Job;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if (Auth::check()) {
        //     $user = Auth::user();
        //     $freelancer = Profile::where('user_id', $user->id)->first();

        //     if ($user->hasRole('hirer')) { 
                
        //     } else {
        //         $ongoingJobsCount = Job::where('profile_id',  $freelancer->id)->where('status', 'assigned')->count();
        //     }
        //     view()->composer('partials.dashboard_sidebar', function ($view) use ($ongoingJobsCount) {
        //         $view->with('ongoing_jobs_count', $ongoingJobsCount);
        //     });
        // }
    }
}
