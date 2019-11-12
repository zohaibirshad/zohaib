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



// Jobs
Route::get('jobs-api', 'JobsController@jobs');
Route::get('job-categories-api', 'JobsController@industries');
Route::get('skills-api', 'JobsController@skills');
Route::get('post-job', 'JobsController@create')->name('post-job');

// Freelancers
Route::get('freelancers-api', 'FreelancersController@freelancers');
Route::get('countries-api', 'FreelancersController@countries');

// Blog
Route::resource('blog', 'BlogController')->only('index', 'show');
Route::get('posts', 'BlogController@posts');
Route::get('posts/tags', 'BlogController@tags');
Route::get('posts/categories', 'BlogController@categories');
Route::get('posts/featured', 'BlogController@featured_posts');
Route::get('posts/trending', 'BlogController@trending_posts');

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
    // Profile Settings
    Route::post('update_password', 'AccountController@update_password');
    Route::post('update_basic_info', 'AccountController@update_basic_info');
    Route::post('update_freelancer_info', 'AccountController@update_freelancer_info');
    Route::post('update_role', 'AccountController@update_role');

    // Freelancers
    Route::get('browse-freelancers', 'FreelancersController@index')->name('freelancers.index');
    Route::resource('freelancers', 'FreelancersController')->except('index');

    // Hirer 
    Route::get('browse-jobs', 'JobsController@index')->name('jobs.index');
    Route::resource('jobs', 'JobsController')->except('index');

    // Freelancer Stuff
    Route::group(['middleware' => ['role:freelancer']], function () {
        Route::get('my-bids', 'FreelancersController@bids')->name('my-bids');
    });

    // Hirer Stuff
    Route::group(['middleware' => ['role:hirer']], function () {
        Route::get('new-jobs', 'HirerController@not_assigned_jobs')->name('new-jobs');

        Route::get('bidders/{id}', 'DashboardController@bidders')->name('bidders');
    });

    Route::get('ongoing-jobs', 'JobsController@ongoing_jobs')->name('ongoing-jobs');

    Route::get('completed-jobs', 'JobsController@completed_jobs_')->name('completed-jobs');
    

    // Both Freelancer and Hirer
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('invites', 'DashboardController@invites')->name('invites');
    Route::resource('bookmarks', 'BookmarksController')->only(['index', 'destroy']);
    Route::post('jobs/bookmarks-toggle-api', 'BookmarksController@toggle_api');
    Route::post('freelancers/bookmarks-toggle-api', 'BookmarksController@toggle_api');

    Route::get('messages', function () {
        return view('dashboard.messages');
    })->name('messages');

    Route::get('reviews', function () {
        return view('dashboard.reviews');
    })->name('reviews');

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
