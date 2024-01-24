<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket_detail extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }

    public function sub_category_ticket()
    {
        return $this->belongsTo('App\Sub_category_ticket');
    }

    public function agent()
    {
        return $this->belongsTo('App\Agent');
    }
}
