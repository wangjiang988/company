<?php

namespace App\Events;

use App\Models\HcDailiAccount;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AccountTooLowEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $daili_account;
    public $old_daili_account;
    public $action;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(HcDailiAccount $daili_account, HcDailiAccount $old_dali_account ,$action='too_low')
    {
        $this->daili_account    =  $daili_account;
        $this->old_daili_account =  $old_dali_account;
        $this->action           =  $action;
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
