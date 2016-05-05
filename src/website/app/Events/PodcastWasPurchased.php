<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PodcastWasPurchased extends Event
{
    use SerializesModels;

    public $info = [];
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($info = 1)
    {
        //
        $this->info = $info;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
