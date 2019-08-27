<?php

namespace App\Models;

use \Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    public function instance()
    {
        return $this->belongsTo(Instance::class);
    }
}
