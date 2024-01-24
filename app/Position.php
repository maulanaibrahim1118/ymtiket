<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

    public function client()
    {
        return $this->hasOne('App\Models\Client');
    }
}
