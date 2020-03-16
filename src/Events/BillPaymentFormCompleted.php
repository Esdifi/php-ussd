<?php

namespace Dbilovd\PHUSSD\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BillPaymentFormCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Session ID
     * 
     * @var String
     */
    public $sessionId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sessionId)
    {
        $this->sessionId = $sessionId;
    }
}
