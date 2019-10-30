<?php

namespace App\Services;

use App\Services\Instances\Bilaxy\BilaxyInstance;
use App\Services\Instances\Bitmex\BitmexInstance;
use App\Services\Instances\Exmo\ExmoInstance;
use App\Services\Instances\Instance;

class InstanceFactoryMethod
{
    private const EXMO_INSTANCE = 'Exmo';
    private const BITMEX_INSTANCE = 'Bitmex';
    private const BILAXY_INSTANCE = 'Bilaxy';

    /**
     * @param string $instance
     * @return Instance|null
     */
    public function getInstance(string $instance)
    {
        switch ($instance) {
            case self::EXMO_INSTANCE:
                return new ExmoInstance();
            case self::BITMEX_INSTANCE:
                return new BitmexInstance();
            case self::BILAXY_INSTANCE:
                return new BilaxyInstance();
        }

        return null;
    }
}
