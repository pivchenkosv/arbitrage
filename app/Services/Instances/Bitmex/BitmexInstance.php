<?php

namespace App\Services\Instances\Bitmex;

use App\Services\Instances\Instance;
use App\Models\Instance as ModelInstance;
use GuzzleHttp\Client;

class BitmexInstance extends Instance
{
    public $name = 'Bitmex';
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
        $response = $this->client->get("orderBook/L2?symbol=$pair&depth=25")
            ->getBody()
            ->getContents();
        return json_decode($response);
    }

    public function acceptOrder()
    {
        // TODO: Implement acceptOrder() method.
    }

    public function fetchOrdersFormatted($pairs)
    {
        $formattedOrders = [];
        $instance_id = ModelInstance::whereName($this->name)->first()->id;

        foreach ($pairs as $pair) {
            $orders = $this->fetchOrders($pair['symbol']);
            $formattedOrders = array_merge($formattedOrders, $this->formatOrders($orders, $instance_id, $pair['id']));
        }

        return $formattedOrders;
    }

    private function formatOrders($offers, $instance_id, $pairId)
    {
        $orders = [];
        foreach ($offers as $offer) {
            $orders[] = [
                'instance_id' => $instance_id,
                'pair_id' => $pairId,
                'type' => $offer->side,
                'rate' => $offer->price,
                'quantity' => null,
                'total' => $offer->size
            ];
        }

        return $orders;
    }
}
