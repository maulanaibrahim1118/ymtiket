<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $guarded = ['id'];

    public function regional()
    {
        return $this->belongsTo('App\Regional');
    }

    public function location()
    {
        return $this->hasOne('App\Location');
    }
}