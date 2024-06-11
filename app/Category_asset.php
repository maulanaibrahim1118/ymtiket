<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_asset extends Model
{
    protected $guarded = ['id'];

    public function asset()
    {
        return $this->hasOne('App\Asset');
    }

    public function item()
    {
        return $this->hasOne('App\Item');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->nama_kategori = strtolower($model->nama_kategori);
        });
    }
}