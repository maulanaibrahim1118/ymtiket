<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket_approval extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }
}
