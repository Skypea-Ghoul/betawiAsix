<?php

namespace App\Events;

use App\Models\PaymentProofs;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   public $payment;
    public function __construct(PaymentProofs $payment) { $this->payment = $payment; }
    public function broadcastOn() { return new Channel('payments'); }
    public function broadcastAs() { return 'PaymentUpdated'; }
}
