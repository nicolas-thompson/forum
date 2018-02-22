<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ThreadHasNewReply
{
    public $thread;

    public $reply;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param \App\Thread $thread
     * @param \App\Reply $reply
     */
    public function __construct($thread, $reply)
    {
        $this->thread;    
        $this->reply;    
    }
}
