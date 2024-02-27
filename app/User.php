<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements Authorizable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

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

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function ticket()
    {
        return $this->hasOne('App\Ticket');
    }

    public function comment()
    {
        return $this->hasOne('App\Comment');
    }

    public function ticket_approval()
    {
        return $this->hasOne('App\Ticket_approval');
    }
}
