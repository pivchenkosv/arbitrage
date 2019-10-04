<?php


namespace App\Services;


use App\Services\Instances\Bitmex\BitmexInstance;
use App\Services\Instances\Exmo\ExmoInstance;

class InstanceFactoryMethod
{
    const EXMO_INSTANCE = 'Exmo';
    const BITMEX_INSTANCE = 'Bitmex';

    /**
     * @param string $instance
     * @return BitmexInstance|ExmoInstance|null
     */
    public function getInstance(string $instance)
    {
        switch ($instance) {
            case self::EXMO_INSTANCE:
                return new ExmoInstance();
            case self::BITMEX_INSTANCE:
                return new BitmexInstance();
        }

        return null;
    }
}
