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

    public function client()
    {
        return $this->hasOne('App\Client');
    }

    public function agent()
    {
        return $this->hasOne('App\Client');
    }

    public function asset()
    {
        return $this->hasOne('App\Asset');
    }
}
