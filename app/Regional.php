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

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = strtolower($model->name);
        });
    }
}