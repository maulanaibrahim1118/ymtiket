<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_category_ticket extends Model
{
    protected $guarded = ['id'];

    public function ticket_details()
    {
        return $this->hasMany('App\Ticket_detail');
    }

    public function category_ticket()
    {
        return $this->belongsTo('App\Category_ticket');
    }
}