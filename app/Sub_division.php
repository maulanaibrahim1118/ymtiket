<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_division extends Model
{
    protected $guarded = ['id'];
    
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function agents()
    {
        return $this->hasMany('App\Agent');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    // Accessor untuk mengkapitalisasi huruf pertama dari setiap kata dalam name
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
}