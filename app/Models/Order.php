<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use \Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    public function instance()
    {
        return $this->belongsTo(Instance::class);
    }
}
