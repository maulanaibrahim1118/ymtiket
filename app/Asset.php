<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->hasOne('App\Models\Ticket');
    }

    public function category_asset()
    {
        return $this->belongsTo('App\Models\Category_asset');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }
}
