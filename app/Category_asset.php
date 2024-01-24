<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_asset extends Model
{
    protected $guarded = ['id'];

    public function asset()
    {
        return $this->hasOne('App\Models\Asset');
    }
}
