<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->hasOne('App\Ticket');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }
}
