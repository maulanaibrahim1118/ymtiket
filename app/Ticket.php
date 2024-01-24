<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = ['id'];

    public function asset()
    {
        return $this->belongsTo('App\Models\Asset');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\Agent');
    }

    public function comment()
    {
        return $this->hasOne('App\Models\Comment');
    }

    public function progress_ticket()
    {
        return $this->hasOne('App\Models\Progress_ticket');
    }

    public function ticket_approval()
    {
        return $this->hasOne('App\Models\Ticket_approval');
    }

    public function ticket_detail()
    {
        return $this->hasOne('App\Models\Ticket_detail');
    }
}
