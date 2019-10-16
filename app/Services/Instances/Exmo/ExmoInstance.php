<?php

namespace App\Services\Instances\Exmo;

use App\Models\Order;
use App\Models\Instance;
use App\Models\Pair;
use App\Services\Instances\Instance as ExchangeInstance;
use GuzzleHttp\Client;

class ExmoInstance extends ExchangeInstance
{
    public $name = 'Exmo';
    protected $uri = 'https://api.exmo.com/v1/';
    protected $delimeter = '_';
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->uri,
        ]);
    }

    public function fetchOrders($pairs)
    {
        $query = $this->getPairsQuery($pairs);

        $response = $this->client->get("order_book/?pair=$query")
            ->getBody()
            ->getContents();
        return json_decode($response);
    }

    public function acceptOrder()
    {
        // TODO: Implement acceptOrder() method.
    }

    private function getPairsQuery($pairs)
    {
        $concatenate = function ($res, $pair) {
            return $res . ',' . $pair['symbol'];
        };

        return trim(array_reduce($pairs, $concatenate), ',');
    }

    public function fetchOrdersFormatted($pairs) {
        $orders = $this->fetchOrders($pairs);

        foreach ($pairs as $pair) {
            $key = $pair['symbol'];
            //TODO: Verify $key existence.
            $this->persistOffers($orders->$key->ask, $pair, 'Sell');
            $this->persistOffers($orders->$key->bid, $pair, 'Buy');
        }
    }
}
