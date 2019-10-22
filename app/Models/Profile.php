<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use DigitalCloud\ModelNotes\HasNotes;
use App\Traits\Uuid;

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

    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'country_id', 'id');
    }

    public function industries()
    {
        return $this->belongsToMany('App\Models\Industry');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Models\Skill');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment');
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