<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;
    
    function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();        
    }

    /** @test */
    function a_thread_can_make_a_string_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->id}", $thread->path()
        );
    }

    /** @test */
    function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }
    
    /** @test */
    function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body'      => 'Foobar',
            'user_id'   => 1 
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');
        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');
        $thread->subscribe($user_id = 1);
        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $user_id)->count());
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from()
    {
        // Given we have a thread.
        $thread = create('App\Thread');
        // And a user who is subscribed to the thread. 
        $thread->subscribe($userId = 1);
        // When the user unsubscribes to the thread.
        $thread->unsubscribe($userId);
        // Then the user should be unsubscribed from the thread.
        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create('App\Thread');
        $this->signIn();
        $this->assertFalse($thread->isSubscribedTo);        
        $thread->subscribe();
        $this->assertTrue($thread->isSubscribedTo);
    }
}