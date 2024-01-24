<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_ticket extends Model
{
    protected $guarded = ['id'];

    public function sub_category_ticket()
    {
        return $this->hasOne('App\Models\Sub_category_ticket');
    }
}
