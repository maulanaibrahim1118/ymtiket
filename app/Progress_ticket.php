<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Progress_ticket extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket');
    }
}
