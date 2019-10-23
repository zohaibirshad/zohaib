<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{

    /**
    * @var  string
    */
    protected $table = 'blog_categories';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = (string) Str::slug($model->title);
        });

        static::updating(function ($model) {
            $model->slug = (string) Str::slug($model->title);
        });
    }


    public function posts()
    {
        return $this->belongsToMany('App\Models\BlogPost');
    }

}