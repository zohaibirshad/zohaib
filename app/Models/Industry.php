<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\SoftDeletes;


class Industry extends Model implements HasMedia
{
    use HasMediaTrait, SoftDeletes;

/**
* @var  string
*/
protected $table = 'industries';

protected $casts = [
'created_at' => 'datetime',
'updated_at' => 'datetime',
];

public function registerMediaCollections()
{
    $this->addMediaCollection('industry');

}

public function profiles()
{
    return $this->belongsToMany('App\Models\Profile');
}

public function job()
{
    return $this->belongsTo('App\Models\Job');
}


}