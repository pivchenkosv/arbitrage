<?php

namespace App\Jobs;

use App\Models\Instance;
use App\Models\Order;
//use App\Services\Exmo\ExmoInstance;
use App\Services\InstanceFactoryMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncExmoOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const INSTANCE_NAME = 'Exmo';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $instanceFactoryMethod = new InstanceFactoryMethod();
        $exmo = $instanceFactoryMethod->getInstance(self::INSTANCE_NAME);
        $pairs = Instance::whereName(self::INSTANCE_NAME)
            ->first()
            ->pairs()
            ->whereIsEnabled(true)
            ->get()
            ->toArray();
        $orders = $exmo->fetchOrders($pairs);

        foreach ($pairs as $pair) {
            $key = $this->concatenate($pair);
            //TODO: Verify $key existence.
            $this->persistOffers($orders->$key->ask, $pair, 'Sell');
            $this->persistOffers($orders->$key->bid, $pair, 'Buy');
        }
    }

    private function concatenate($pair)
    {
        return $pair['first_currency'] .
            '_' .
            $pair['second_currency'];
    }

    private function persistOffers($offers, $pair, $type = 'Sell')
    {
        foreach ($offers as $offer) {
            $order = new Order();
            $order->instance_id = Instance::whereName(self::INSTANCE_NAME)->first()->id;
            $order->pair_id = $pair['_id'];
            $order->type = $type;
            $order->rate = $offer[0];
            $order->quantity = $offer[1];
            $order->total = $offer[2];

            $order->save();
        }
    }
}
