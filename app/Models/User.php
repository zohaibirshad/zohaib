<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use DigitalCloud\ModelNotes\HasNotes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;



class User extends Authenticatable implements Auditable
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
    ];
    
    protected $appends = [
        'name'
    ];

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
}
