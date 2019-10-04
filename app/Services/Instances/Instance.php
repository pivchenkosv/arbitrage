<?php


namespace App\Services\Instances;


abstract class Instance
{
    protected $uri;
    abstract public function fetchOrders($pairs);
    abstract public function acceptOrder();
}
