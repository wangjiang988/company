<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;//加入队列

class SendShipmentNotification implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * 队列化任务使用的连接名称。
     *
     * @var string|null
     */
    public $connection = 'sqs';

    /**
     * 队列化任务使用的队列名称。
     *
     * @var string|null
     */
    public $queue = 'listeners';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @param $exception 处理失败任务
     * @return void
     */
    public function handle(OrderShipped $event , $exception)
    {
        if (true) {
            $this->release(30);
        }
        //使用 $event->order 来访问 order
    }
}
