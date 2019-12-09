<?php

use Illuminate\Http\Request;
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

Auth::routes(['verify' => true]);
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');






Route::namespace("Auth")->group(function () {
    Route::get('login/{provider}', 'LoginController@redirectToProvider');
    Route::get('login/{provider}/callback', 'LoginController@handleProviderCallback');
});

Route::get('/', 'ApplicationController@index')->name('home');

Route::get('how-it-works', function () {
    return view('how_it_works');
})->name('how-it-works');

Route::post('billing/paymentmethod/update', function(Request $request){

    $user = Auth::user();

    // $user->createAsStripeCustomer();

    if ($user->hasPaymentMethod()) {
        $user->updateDefaultPaymentMethod($request->method);
    }else {
        $user->addPaymentMethod($request->method);
        $user->updateDefaultPaymentMethod($request->method);
    } 

    return response()->json([
        'message' => "Payment Method Added Successful",
        'data' => $user->defaultPaymentMethod()
    ]);
});

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

    $plans = App\Models\Plan::oldest()->get();
    return view('subscription.pricing', compact('plans'));

})->name('pricing');

Route::get('checkout/{id}', function ($id) {
    $plan = App\Models\Plan::find($id);


    $intent = Auth::user()->createSetupIntent();


    return view('subscription.checkout', compact('plan', 'intent'));

})->name('checkout');

Route::post('order-confirmation', function (Request $request) {
    $validateData = $request->validate([
        'plan' => 'integer|required'
    ]);
    $plan = App\Models\Plan::find($request->plan);

    $user = Auth::user();

    $paymentMethod = $user->defaultPaymentMethod();

    $user->newSubscription('bins', $plan->plan_id)->create($paymentMethod->id);

    return view('subscription.confirmation', compact('plan'));
})->name('confirmation');

Route::get('invoice', function () {

    $user = Auth::user();

    $invoice = $user->invoices()->first();

    return Auth::user()->downloadInvoice($invoice->id, [
        'vendor' => 'Yohli',
        'product' => 'Bids',
    ]);
})->name('invoice');



// DASHBOARD STUFF
Route::group(['middleware' => ['auth', 'verified']], function () {
    // Profile Settings
    Route::post('update_password', 'AccountController@update_password');
    Route::post('update_basic_info', 'AccountController@update_basic_info');
    Route::post('update_freelancer_info', 'AccountController@update_freelancer_info');
    Route::post('update_freelancer_documents', 'AccountController@update_freelancer_documents');
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
        Route::get('reject-invite/{uuid}', 'FreelancersController@reject_invite')->name('reject.invite');
        Route::put('accept-invite/{uuid}', 'FreelancersController@accept_invite')->name('accept.invite');
        Route::post('make_bid/{uuid}', 'FreelancersController@make_bid');
        Route::post('edit_bid/{uuid}', 'FreelancersController@edit_bid');
        Route::post('delete_bid/{uuid}', 'FreelancersController@delete_bid');
        Route::post('review_job/{uuid}', 'FreelancersController@review_job');
        Route::post('milestones/update_milestone/{uuid}', 'FreelancersController@update_milestone');
        Route::post('milestones/add', 'FreelancersController@add_milestone');
    });

    // Hirer Stuff
    Route::group(['middleware' => ['role:hirer']], function () {
        Route::get('new-jobs', 'HirerController@not_assigned_jobs')->name('new-jobs');
        Route::post('new-invite', 'HirerController@send_invite')->name('new-invite');
        Route::post('review_freelancer/{uuid}', 'HirerController@review_freelancer');
        Route::get('bidders/{uuid}', 'HirerController@manage_bids')->name('bidders');
        Route::post('bidders/accept_bid/{uuid}', 'HirerController@accept_bid');
        Route::post('milestones/release_payment/{uuid}', 'HirerController@release_payment_for_milestone');
    });

    Route::get('ongoing-jobs', 'JobsController@ongoing_jobs')->name('ongoing-jobs');

    Route::get('completed-jobs', 'JobsController@completed_jobs_')->name('completed-jobs');
    

    // Both Freelancer and Hirer
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('invites', 'DashboardController@invites')->name('invites');
    Route::resource('bookmarks', 'BookmarksController')->only(['index', 'destroy']);
    Route::post('jobs/bookmarks-toggle-api', 'BookmarksController@toggle_api');
    Route::post('freelancers/bookmarks-toggle-api', 'BookmarksController@toggle_api');
    Route::get('reviews', 'ReviewsController@index')->name('reviews');

    Route::get('messages', function () {
        return view('dashboard.messages');
    })->name('messages');

    Route::get('settings', 'DashboardController@settings')->name('settings');
    Route::get('verify-profile', 'DashboardController@verify');

    Route::get('milestones/{id}', 'DashboardController@milestones')->name('milestones');


    // Finances
    Route::get('add-funds', function () {
        return view('dashboard.finances.add_funds');
    })->name('add-funds');

    Route::get('withdraw-funds', function () {
        return view('dashboard.finances.withdraw_funds');
    })->name('withdraw-funds');
});


Route::post(
    'stripe/webhook',
    '\App\Http\Controllers\WebhookController@handleWebhook'
);