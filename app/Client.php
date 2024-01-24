<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->hasOne('App\Models\Ticket');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Position');
    }
}
