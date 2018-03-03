<?php

namespace App\Listeners;

use App\User;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWhereMentioned;

class NotifyMentionedUsers
{
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
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        $mentionedUsers = $event->reply->mentionedUsers();

        foreach($usernames[1] as $name) {
            $user = User::whereName($name)->first();
            if($user){
                $user->notify(new YouWhereMentioned($event->reply));
            }
        }
    }
}
