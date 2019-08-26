<?php


namespace App\Services;


abstract class Instance
{
    protected $uri;
    abstract public function fetchOrders();
    abstract public function acceptOrder();
}
