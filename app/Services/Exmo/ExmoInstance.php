<?php


namespace App\Services\Exmo;


use App\Models\Order;
use App\Services\Instance;
use GuzzleHttp\Client;
use Psy\Util\Json;

class ExmoInstance extends Instance
{
    protected $uri = "https://api.exmo.com/v1/";

    public function fetchOrders($pair = "BTC_USD")
    {
        // TODO: Implement fetchOrders() method.
        $client = new Client([
            'base_uri' => $this->uri,
        ]);

        $response = $client->request('GET', "order_book/?pair=$pair");

        $this->persistOffers($response->ask, $pair, "Sell");
        $this->persistOffers($response->bid, $pair, "Buy");
    }

    public function acceptOrder()
    {
        // TODO: Implement acceptOrder() method.
    }

    private function persistOffers($offers, $pair, $type = "Sell")
    {
        foreach ($offers as $offer) {
            $order = new Order();
            $order->instance_id = "5d63ce5ba409e15f514a9643";
            $order->first_currency = explode("_" ,$pair)[0];
            $order->second_currency = explode("_" ,$pair)[1];
            $order->type = $type;
            $order->rate = $offer[0];
            $order->quantity = $offer[1];
            $order->total = $offer[2];

            $order->save();
        }
    }
}
