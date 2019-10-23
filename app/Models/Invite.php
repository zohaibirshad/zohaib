<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DigitalCloud\ModelNotes\HasNotes;
use App\Traits\Uuid;

class Invite extends Model
{
    use HasNotes, Uuid;

    /**
    * @var  string
    */
    protected $table = 'invites';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id')->with('media');
    }
    public function job()
    {
        return $this->belongsTo('App\Models\Job', 'job_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment');
    }
}