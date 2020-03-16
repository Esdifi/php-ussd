<?php

namespace Dbilovd\PHUSSD\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class IssueRecorded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Issue Object created
     * 
     * @var Array
     */
    public $issue;

    /**
     * Session ID that created issue
     * 
     * @var String
     */
    public $sessionId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($issue, $sessionId)
    {
        $this->issue = $issue;
        $this->sessionId = $sessionId;
    }
}
