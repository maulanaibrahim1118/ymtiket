<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasMany('App\User');
    }

    public function client()
    {
        return $this->hasMany('App\Client');
    }

    public function agent()
    {
        return $this->hasMany('App\Client');
    }

    public function asset()
    {
        return $this->hasMany('App\Asset');
    }

    public function ticket()
    {
        return $this->hasMany('App\Asset');
    }

    public function category_ticket()
    {
        return $this->hasMany('App\Category_ticket');
    }
}
