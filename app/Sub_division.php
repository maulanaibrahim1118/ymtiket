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

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = strtolower($model->name);
        });
    }

    // Accessor untuk mengkapitalisasi huruf pertama dari setiap kata dalam name
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
}