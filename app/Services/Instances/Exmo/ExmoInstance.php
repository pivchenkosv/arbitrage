<?php

namespace App\Services\Instances\Exmo;

use App\Models\Instance as ModelInstance;
use App\Services\Instances\Instance;
use GuzzleHttp\Client;

class ExmoInstance extends Instance
{
    public $name = 'Exmo';
    protected $uri = 'https://api.exmo.com/v1/';
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->uri,
        ]);
    }

    public function fetchOrders($pairs)
    {
        $response = $this->client->get("order_book/?pair=$pairs")
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

        return trim(array_reduce($pairs, $concatenate, ','));
    }

    public function fetchOrdersFormatted($pairs)
    {
        $orders = $this->fetchOrders($this->getPairsQuery($pairs));
        $formattedOrders = [];
        $instance_id = ModelInstance::whereName($this->name)->first()->id;

        foreach ($pairs as $pair) {
            $key = $pair['symbol'];
            //TODO: Verify $key existence.
            $formattedOrders = array_merge(
                $formattedOrders,
                $this->formatOrders($orders->$key->ask, $instance_id, $pair['id'], 'Sell')
            );
            $formattedOrders = array_merge(
                $formattedOrders,
                $this->formatOrders($orders->$key->bid, $instance_id, $pair['id'], 'Buy')
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
