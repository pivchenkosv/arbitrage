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
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private const INSTANCE_NAME = 'Bitmex';

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
        $orders = [];
        $instance_id = Instance::whereName(self::INSTANCE_NAME)->first()->id;
        foreach ($offers as $offer) {
            $orders[] = [
            'instance_id' => $instance_id,
            'pair_id' => $pair['id'],
            'type' => $offer->side,
            'rate' => $offer->price,
            'quantity' => null,
            'total' => $offer->size
            ];
        }

        Order::insert($orders);
    }
}
