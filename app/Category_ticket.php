<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_ticket extends Model
{
    protected $guarded = ['id'];

    public function sub_category_tickets()
    {
        return $this->hasMany('App\Sub_category_ticket');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}