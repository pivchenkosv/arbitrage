<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use \Jenssegers\Mongodb\Eloquent\Model;

class Instance extends Model
{
    public function pairs()
    {
        return $this->hasMany(Pair::class);
    }
}
