<?php

namespace App\Console\Commands;

use App\Models\HgOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OrderSincerity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:sincerity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order Selection Timeout operation';

    protected $order;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HgOrder $order)
    {
        $this->order = $order;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = $this->alculateOrders();
        if (empty($result)) {
            return false;
        }
        $id = array_column($result, 'id');
        $status = array_merge(array_column($result, 'status'), array_column($result, 'state'));
        $this->order->batchUpdate('id', 'order_status', 'order_state', $id, $status);

    }


    public function alculateOrders()
    {
        $end_time = Carbon::now();
        $bond = [];
        $this->order->whereIn('order_state', [
            config('orders.order_earnest_not_confirm'),
            config('orders.order_earnest_not_confirm_file')
        ])
            ->where('rockon_time', '<=', $end_time)
            ->chunk(10, function ($orders) use (&$bond) {
                foreach ($orders as $order) {
                    $bond[] = [
                        'id'     => $order->id,
                        'status' => config('orders.order_doposit'),
                        'state'  => config('orders.order_earnest_backok')
                    ];
                }
            });

        $accord = [];
        $this->order->whereIn('order_state', [
            config('orders.order_earnest_confirm'),
            config('orders.order_earnest_eidt3')
        ])
            ->where('rockon_time', '<=', $end_time)
            ->chunk(10, function ($orders) use (&$accord) {
                foreach ($orders as $order) {
                    $accord[] = [
                        'id'     => $order->id,
                        'status' => config('orders.order_earnest'),
                        'state'  => config('orders.order_seller_time_end')
                    ];
                }
            });

        $driving = [];
        $this->order->whereIn('order_state', [
            config('orders.order_earnest_eidt1'),
            config('orders.order_earnest_edit2')
        ])
            ->where('rockon_time', '<=', $end_time)
            ->chunk(10, function ($orders) use (&$driving) {
                foreach ($orders as $order) {
                    $driving[] = [
                        'id'     => $order->id,
                        'status' => config('orders.order_earnest'),
                        'state'  => config('orders.order_seller_earnest_end')
                    ];
                }
            });

        return $result = array_merge($bond, $accord, $driving);
    }


}
