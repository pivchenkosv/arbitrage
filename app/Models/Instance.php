<?php

namespace App\Models;

use \Jenssegers\Mongodb\Eloquent\Model;

class Instance extends Model
{
    public function pairs()
    {
        return $this->hasMany(Pair::class);
    }
}
