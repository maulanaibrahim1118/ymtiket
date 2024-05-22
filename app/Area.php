<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = ['id'];

    public function regional()
    {
        return $this->hasOne('App\Regional');
    }
}