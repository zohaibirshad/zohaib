<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Job;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
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
        $user = Auth::user();

        if (!empty($user)) {
            $freelancer = Profile::where('user_id', $user->id)->first();

            if ($user->hasRole('hirer')) { 
                $ongoingJobsCount = Job::where('user_id',  $freelancer->id)->where('status', 'assigned')->count();
            } else {
                $ongoingJobsCount = Job::where('profile_id',  $freelancer->id)->where('status', 'assigned')->count();
            }
            View::composer('partials.dashboard_sidebar', function ($view) use ($ongoingJobsCount) {
                $view->with('ongoing_jobs_count', $ongoingJobsCount);
            });
        }
    }
}