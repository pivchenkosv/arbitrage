<?php


namespace App\Services\Exmo;


use App\Models\Order;
use App\Models\Instance;
use App\Services\Instance as ExchangeInstance;
use GuzzleHttp\Client;

class ExmoInstance extends ExchangeInstance
{
    protected $uri = "https://api.exmo.com/v1/";

    public function fetchOrders($pair = "BTC_USD")
    {
        $client = new Client([
            'base_uri' => $this->uri,
        ]);

        $response = $client->get("order_book/?pair=$pair")
            ->getBody()
            ->getContents();
        $response = json_decode($response);

        $this->persistOffers($response->$pair->ask, $pair, "Sell");
        $this->persistOffers($response->$pair->bid, $pair, "Buy");
    }

    public function acceptOrder()
    {
        // TODO: Implement acceptOrder() method.
    }

    private function persistOffers($offers, $pair, $type = "Sell")
    {
        foreach ($offers as $offer) {
            $order = new Order();
            $order->instance_id = Instance::whereName('Exmo')->first()->id;
            $order->first_currency = explode("_", $pair)[0];
            $order->second_currency = explode("_", $pair)[1];
            $order->type = $type;
            $order->rate = $offer[0];
            $order->quantity = $offer[1];
            $order->total = $offer[2];

            $order->save();
        }
    }
}
