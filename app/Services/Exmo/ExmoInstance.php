<?php


namespace App\Services\Exmo;


use App\Models\Order;
use App\Models\Instance;
use App\Models\Pair;
use App\Services\Instance as ExchangeInstance;
use GuzzleHttp\Client;

class ExmoInstance extends ExchangeInstance
{
    protected $uri = 'https://api.exmo.com/v1/';
    protected $delimeter = '_';

    public function fetchOrders()
    {
        $client = new Client([
            'base_uri' => $this->uri,
        ]);
        $pairs = Instance::whereName('Exmo')
            ->first()
            ->pairs()
            ->whereIsEnabled(true)
            ->get()
            ->toArray();
        $query = $this->getPairsQuery($pairs);

        $response = $client->get("order_book/?pair=$query")
            ->getBody()
            ->getContents();
        $response = json_decode($response);

        foreach ($pairs as $pair) {
            $key = $this->concatenate($pair);
            //TODO: Verify $key existence.
            $this->persistOffers($response->$key->ask, $pair, 'Sell');
            $this->persistOffers($response->$key->bid, $pair, 'Buy');
        }
    }

    public function acceptOrder()
    {
        // TODO: Implement acceptOrder() method.
    }

    private function persistOffers($offers, $pair, $type = 'Sell')
    {
        foreach ($offers as $offer) {
            $order = new Order();
            $order->instance_id = Instance::whereName('Exmo')->first()->id;
            $order->pair_id = $pair['_id'];
            $order->type = $type;
            $order->rate = $offer[0];
            $order->quantity = $offer[1];
            $order->total = $offer[2];

            $order->save();
        }
    }

    private function concatenate($pair)
    {
        return $pair['first_currency'] .
            $this->delimeter .
            $pair['second_currency'];
    }

    private function getPairsQuery($pairs)
    {
        $concatenate = function($res, $el) {
            return $res . ',' . $this->concatenate($el);
        };

        return trim(array_reduce($pairs, $concatenate), ',');
    }
}
