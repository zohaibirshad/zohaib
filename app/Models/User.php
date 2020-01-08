<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use DigitalCloud\ModelNotes\HasNotes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;



class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use Notifiable, Billable, HasRoles, HasApiTokens, HasNotes, AuditTrait, SoftDeletes;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'profile_verified_at' => 'datetime',
    ];

     /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {

            $model->createAsStripeCustomer();

            Account::createWithAttributes(['name' => 'walet', 'user_id' => $model->id]);

        });

    }
    

    public function getNameAttribute() 
    {
        return "{$this->first_name} {$this->last_name}";
    } 


    public function profile()
    {
        return $this->hasOne('App\Models\Profile')->with('media', 'country');
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function getFullnameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function account()
    {
        return $this->hasOne('App\Models\Account');
    }

    public function plan()
    {
        return $this->belongsToMany('App\Models\Plan')->withTimestamps()->withPivot('count');
    }
}

