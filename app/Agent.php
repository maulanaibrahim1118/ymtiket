<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->hasMany('App\Ticket');
    }

    public function ticket_details()
    {
        return $this->hasMany('App\Ticket_detail');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function sub_divisions()
    {
        return $this->belongsToMany('App\Sub_divisi');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->sub_divisi = strtolower($model->sub_divisi);
        });
    }
}