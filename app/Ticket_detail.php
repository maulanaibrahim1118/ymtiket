<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket_detail extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket');
    }

    public function sub_category_ticket()
    {
        return $this->belongsTo('App\Models\Sub_category_ticket');
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\Agent');
    }
}
