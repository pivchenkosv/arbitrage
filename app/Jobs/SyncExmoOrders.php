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
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private const INSTANCE_NAME = 'Exmo';

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
            $key = $pair['symbol'];
            //TODO: Verify $key existence.
            $this->persistOffers($orders->$key->ask, $pair, 'Sell');
            $this->persistOffers($orders->$key->bid, $pair, 'Buy');
        }
    }

    private function persistOffers($offers, $pair, $type = 'Sell')
    {
        $orders = [];
        $instance_id = Instance::whereName(self::INSTANCE_NAME)->first()->id;
        foreach ($offers as $offer) {
            $orders[] = [
                'instance_id' => $instance_id,
                'pair_id' => $pair['id'],
                'type' => $type,
                'rate' => $offer[0],
                'quantity' => $offer[1],
                'total' => $offer[2]
            ];
        }

        Order::insert($orders);
    }
}
