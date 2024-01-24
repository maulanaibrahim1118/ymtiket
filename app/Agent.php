<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->hasOne('App\Ticket');
    }

    public function ticket_detail()
    {
        return $this->hasOne('App\Ticket_detail');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
