<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        // Given we have a thread...
        $thread = create('App\Thread');
        // And the user subscribes...
        $this->post($thread->path() .  '/subscriptions');
        // Then each time a reply is left...
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply'
        ]);
        // A notification should be prepared for the user.
        // $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();

        // Given we have a thread...
        $thread = create('App\Thread');

        $thread->subscribe();

        $this->delete($thread->path() .  '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
        
    }
}
