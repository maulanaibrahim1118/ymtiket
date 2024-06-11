<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = ['id'];

    public function category_asset()
    {
        return $this->belongsTo('App\Category_asset');
    }

    public function asset()
    {
        return $this->hasOne('App\Asset');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = ucwords($model->name);
            $model->uom = strtolower($model->uom);
        });
    }
}