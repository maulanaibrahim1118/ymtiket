<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    protected $guarded = ['id'];

    public function area()
    {
        return $this->belongsTo('App\Area');
    }

    public function wilayah()
    {
        return $this->hasOne('App\Wilayah');
    }
}