<?php

namespace App\Events;

use App\Models\HgBaojia;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CheckBaojiaEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $baojia;

    //ordered   下单操作，数量-1
    //rollback   订单取消，数量+1
    public $operation;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(HgBaojia $baojia, $operation='ordered')
    {
        $this->baojia = $baojia;
        $this->operation = $operation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
