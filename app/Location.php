<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function sub_divisi()
    {
        return $this->hasOne('App\Sub_divisi');
    }

    public function agent()
    {
        return $this->hasMany('App\Client');
    }

    public function asset()
    {
        return $this->hasMany('App\Asset');
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }

    public function category_ticket()
    {
        return $this->hasMany('App\Category_ticket');
    }

    public function wilayah()
    {
        return $this->belongsTo('App\Wilayah');
    }
}