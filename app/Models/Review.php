<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    /**
    * @var  string
    */
    protected $table = 'reviews';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];


     /**
     * Get the owning reviewable model.
     */
    public function reviewable()
    {
        return $this->morphTo();
    }



}