<?php

namespace App\Services\Instances\Bitmex;

use App\Models\Order;
use App\Services\Instances\Instance;
use App\Models\Instance as ModelInstance;
use GuzzleHttp\Client;

class BitmexInstance extends Instance
{
    public $name = 'Exmo';
    protected $uri = 'https://www.bitmex.com/api/v1/';
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->uri,
        ]);
    }

    public function fetchOrders($pair)
    {
        $query = $pair['first_currency'] . $pair['second_currency'];

        $response = $this->client->get("orderBook/L2?symbol=$query&depth=25")
            ->getBody()
            ->getContents();
        return json_decode($response);
    }

    public function acceptOrder()
    {
        // TODO: Implement acceptOrder() method.
    }
}
