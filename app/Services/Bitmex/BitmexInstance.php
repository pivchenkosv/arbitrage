<?php


namespace App\Services\Bitmex;


use App\Models\Order;
use App\Services\Instance;
use App\Models\Instance as ModelInstance;
use GuzzleHttp\Client;

class BitmexInstance extends Instance
{
    protected $uri = 'https://www.bitmex.com/api/v1/';

    public function fetchOrders()
    {
        // TODO: Implement fetchOrders() method.
        $client = new Client([
            'base_uri' => $this->uri,
        ]);
        $pair = ModelInstance::whereName('Bitmex')
            ->first()
            ->pairs()
            ->whereIsEnabled(true)
            ->get()
            ->first();
        $query = $pair->first_currency . $pair->second_currency;

        $response = $client->get("orderBook/L2?symbol=$query&depth=25")
            ->getBody()
            ->getContents();
        $response = json_decode($response);

        $this->persistOffers($response, $pair);
    }

    public function acceptOrder()
    {
        // TODO: Implement acceptOrder() method.
    }

    private function persistOffers($offers, $pair)
    {
        foreach ($offers as $offer) {
            $order = new Order();
            $order->instance_id = ModelInstance::whereName('Bitmex')->first()->id;
            $order->pair_id = $pair->id;
            $order->type = $offer->side;
            $order->rate = $offer->price;
            $order->quantity = null;
            $order->total = $offer->size;

            $order->save();
        }
    }
}
