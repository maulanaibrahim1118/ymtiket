<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $guarded = ['id'];

    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->no_asset = strtoupper($model->no_asset);
            $model->merk = ucwords($model->merk);
        });
    }
}