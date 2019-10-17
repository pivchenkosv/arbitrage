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

class SyncOrders implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $instance;

    /**
     * Create a new job instance.
     *
     * @param $instance
     */
    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $instanceFactoryMethod = new InstanceFactoryMethod();
        $instance = $instanceFactoryMethod->getInstance($this->instance);
        $pairs = Instance::whereName($this->instance)
            ->first()
            ->pairs()
            ->whereIsEnabled(true)
            ->get()
            ->toArray();

        $orders = $instance->fetchOrdersFormatted($pairs);
        Order::insert($orders);
    }
}
