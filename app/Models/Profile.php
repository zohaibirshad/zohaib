<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use DigitalCloud\ModelNotes\HasNotes;
use App\Traits\Uuid;
use Spatie\MediaLibrary\Models\Media;

class Profile extends Model implements HasMedia
{
    use HasMediaTrait, HasNotes, Uuid;
    /**
     * @var  string
     */
    protected $table = 'profiles';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'completion_rate', 
        'completion_time_rate', 
        'completion_budget_rate', 
        'rating', 'photo'
    ];

    public function getCompletionRateAttribute()
    {
        $jobs_count = $this->jobs()->count();
        $jobs_completed = $this->jobs_completion()->count();

        if($jobs_count <= 0 ){
            return 0;
        }

        $rate = ($jobs_completed /  $jobs_count) * 100;

         return $rate;
    }

    public function getCompletionTimeRateAttribute()
    {
        $jobs_count = $this->jobs()->count();
        $jobs_completed = $this->jobs_completion()->where('ontime', 'yes')->count();

        if($jobs_count <= 0 ){
            return 0;
        }

        $rate = ($jobs_completed /  $jobs_count) * 100;

         return $rate;
    }

    public function getCompletionBudgetRateAttribute()
    {
        $jobs_count = $this->jobs()->count();
        $jobs_completed = $this->jobs_completion()->where('onbudget', 'yes')->count();

        if($jobs_count <= 0 ){
            return 0;
        }

        $rate = ($jobs_completed /  $jobs_count) * 100;

         return $rate;
    }


    public function getRatingAttribute()
    {
        $rating = $this->reviews()->average('rating');

        return number_format($rating, 1);
    }

    public function getPhotoAttribute()
    {
        return $this->getFirstMediaUrl('profile', 'big');
    }
    

    protected $fillable = ['name', 'email', 'type'];

    public function registerMediaCollections()
    {
        $this->addMediaCollection('profile');
        $this->addMediaCollection('cv');
    }

    public function registerMediaConversions(Media $media = null)
    {
        // $this->addMediaConversion('thumb')
        //     ->width(100)
        //     ->height(100)->performOnCollections('profile');

        $this->addMediaConversion('thumb')
            ->crop('crop-center', 100, 100)
            ->performOnCollections('profile');

        $this->addMediaConversion('big')
        ->crop('crop-center', 300, 300)
        ->performOnCollections('profile');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }


    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Models\Skill');
    }

    public function social_links()
    {
        return $this->hasMany('App\Models\SocialLink');
    }

    public function bids()
    {
        return $this->hasMany('App\Models\Bid');
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }

    public function jobs_completion()
    {
        return $this->jobs()->where('status', 'completed');
    }


    /**
     * Get all of the user's reviews.
     */
    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable');
    }
}
