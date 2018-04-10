<?php

namespace App\Payment\Events;

use App\Translation\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FailedChargingUserForMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The Message that failed to charge.
     *
     * @var Message
     */
    public $message;
    /**
     * Attempted charge amount.
     *
     * @var int
     */
    public $chargeAmount;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param $chargeAmount
     */
    public function __construct(Message $message, $chargeAmount)
    {
        $this->message = $message;
        $this->chargeAmount = $chargeAmount;
    }

}
