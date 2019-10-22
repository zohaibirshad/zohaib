<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DigitalCloud\ModelNotes\HasNotes;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;


class Job extends Model
{
    use HasNotes, SoftDeletes, Uuid;

    /**
    * @var  string
    */
    protected $table = 'jobs';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile')->with('media');
    }

    public function industry()
    {
        return $this->belongsTo('App\Models\Industry', 'industry_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }
    public function job_budget()
    {
        return $this->belongsTo('App\Models\JobBudget', 'job_budget_id', 'id');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Models\Skill');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment');
    }

    public function bids()
    {
        return $this->hasMany('App\Models\Bid');
                        
    }

    public function accepted_bid()
    {
        return $this->hasOne('App\Models\Bid')->where('status', 'accepted');
                        
    }

    /**
     * Get all of the job's reviews.
     */
    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable');
    }

    /**
     * Get all of the job's reviews.
     */
    public function payments()
    {
        return;
    }


}