<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_new_reply()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();
        $this->assertCount(0, auth()->user()->notifications);
        
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
        // A notification should be prepared for the user.
        $this->assertCount(1, $thread->subscriptions);
    }
}
