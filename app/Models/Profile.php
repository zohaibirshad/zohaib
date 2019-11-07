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

    public function registerMediaCollections()
    {
        $this->addMediaCollection('profile');
        $this->addMediaCollection('cv');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)->performOnCollections('profile');

        $this->addMediaConversion('big')
            ->width(300)
            ->height(300)->performOnCollections('profile');
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
