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

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->nama_kategori = strtolower($model->nama_kategori);
        });
    }
}