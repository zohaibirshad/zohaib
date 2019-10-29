<?php

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::namespace("Auth")->group(function () {
    Route::get('login/{provider}', 'LoginController@redirectToProvider');
    Route::get('login/{provider}/callback', 'LoginController@handleProviderCallback');
});

Route::get('/', 'ApplicationController@index')->name('home');

// Route::get('login', function () {
//     return view('auth.login');
// })->name('login.page'); 

// Route::get('register', function () {
//     return view('auth.register');
// })->name('register.page');

Route::get('how-it-works', function () {
    return view('how_it_works');
})->name('how-it-works');

Route::get('post-job', function () {
    return view('dashboard.post_job');
})->name('post-job');



// Jobs
Route::get('browse-jobs', 'JobsController@index')->name('jobs.index');
Route::resource('jobs', 'JobsController')->except('index');
Route::get('jobs-api', 'JobsController@jobs');
Route::get('job-categories-api', 'JobsController@industries');
Route::get('skills-api', 'JobsController@skills');

// Freelancers
Route::get('browse-freelancers', 'FreelancersController@index')->name('freelancers.index');
Route::resource('freelancers', 'FreelancersController')->except('index');

// Blog
Route::resource('blog', 'BlogController')->only('index', 'show');
Route::get('posts', 'BlogController@posts');

// Subscription
Route::get('pricing', function () {
    return view('subscription.pricing');
})->name('pricing');
Route::get('checkout', function () {
    return view('subscription.checkout');
})->name('checkout');
Route::get('order-confirmation', function () {
    return view('subscription.confirmation');
})->name('confirmation');
Route::get('invoice', function () {
    return view('subscription.invoice');
})->name('invoice');



// DASHBOARD STUFF
Route::group(['middleware' => ['auth']], function () {
    // Freelancer Stuff
    Route::group(['middleware' => ['role:freelancer']], function () {
        Route::get('my-bids', 'FreelancersController@bids')->name('my-bids');
        Route::get('invites', 'FreelancersController@invites')->name('invites');
    });

    // Hirer Stuff
    Route::group(['middleware' => ['role:hirer']], function () {
        Route::get('new-jobs', function () {
            return view('dashboard.jobs.new_jobs');
        })->name('new-jobs');

        Route::get('bidders/{id}', function () {
            return view('dashboard.bidders');
        })->name('bidders');
    });

    Route::get('ongoing-jobs', 'JobsController@ongoing_jobs')->name('ongoing-jobs');

    Route::get('completed-jobs', 'JobsController@completed_jobs_')->name('completed-jobs');

    // Both Freelancer and Hirer
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('messages', function () {
        return view('dashboard.messages');
    })->name('messages');

    Route::get('reviews', function () {
        return view('dashboard.reviews');
    })->name('reviews');

    Route::get('bookmarks', function () {
        return view('dashboard.bookmarks');
    })->name('bookmarks');

    Route::get('settings', 'DashboardController@settings')->name('settings');

    Route::get('milestones/{id}', 'DashboardController@milestones')->name('milestones');


    // Finances
    Route::get('add-funds', function () {
        return view('dashboard.finances.add_funds');
    })->name('add-funds');

    Route::get('withdraw-funds', function () {
        return view('dashboard.finances.withdraw_funds');
    })->name('withdraw-funds');
});
