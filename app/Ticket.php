<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = ['id'];

    public function asset()
    {
        return $this->belongsTo('App\Asset');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function agent()
    {
        return $this->belongsTo('App\Agent');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function comment()
    {
        return $this->hasMany('App\Comment');
    }

    public function progress_ticket()
    {
        return $this->hasMany('App\Progress_ticket');
    }

    public function ticket_approval()
    {
        return $this->hasOne('App\Ticket_approval');
    }

    public function ticket_detail()
    {
        return $this->hasMany('App\Ticket_detail');
    }
}
