<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->hasMany('App\Ticket');
    }

    public function ticket_details()
    {
        return $this->hasMany('App\Ticket_detail');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}