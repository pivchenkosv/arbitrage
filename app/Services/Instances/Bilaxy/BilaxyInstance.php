<?php

namespace App\Services\Instances\Bilaxy;

use App\Models\Instance as ModelInstance;
use App\Services\Instances\Instance;
use GuzzleHttp\Client;

class BilaxyInstance extends Instance
{
    public $name = 'Bilaxy';
    protected $uri = 'https://api.bilaxy.com';
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->uri,
        ]);
    }

    public function fetchOrders($pair)
    {
        $response = $this->client->get("/v1/depth?symbol=$pair")
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
            $formattedOrders = array_merge(
                $formattedOrders,
                $this->formatOrders($orders->data->asks, $instance_id, $pair['id'], 'Sell')
            );
            $formattedOrders = array_merge(
                $formattedOrders,
                $this->formatOrders($orders->data->bids, $instance_id, $pair['id'], 'Buy')
            );
        }

        return $formattedOrders;
    }

    private function formatOrders($offers, $instance_id, $pairId, $type = 'Sell')
    {
        $orders = [];
        foreach ($offers as $offer) {
            $orders[] = [
                'instance_id' => $instance_id,
                'pair_id' => $pairId,
                'type' => $type,
                'rate' => $offer[0],
                'quantity' => $offer[1],
                'total' => $offer[2]
            ];
        }

        return $orders;
    }
}
