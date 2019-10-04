<?php

namespace App\Jobs;

use App\Models\Instance;
use App\Models\Order;
use App\Services\InstanceFactoryMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncBitmexOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const INSTANCE_NAME = 'Bitmex';

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
        $bitmex = $instanceFactoryMethod->getInstance(self::INSTANCE_NAME);
        $pairs = Instance::whereName(self::INSTANCE_NAME)
            ->first()
            ->pairs()
            ->whereIsEnabled(true)
            ->get()
            ->toArray();

        foreach ($pairs as $pair) {
            $orders = $bitmex->fetchOrders($pair);
            $this->persistOrders($orders, $pair);
        }
    }

    private function persistOrders($offers, $pair)
    {
        foreach ($offers as $offer) {
            $order = new Order();
            $order->instance_id = Instance::whereName(self::INSTANCE_NAME)->first()->id;
            $order->pair_id = $pair['_id'];
            $order->type = $offer->side;
            $order->rate = $offer->price;
            $order->quantity = null;
            $order->total = $offer->size;

            $order->save();
        }
    }
}
