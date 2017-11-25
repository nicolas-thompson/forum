<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;
   
    /** @test */
    public function a_user_can_browse_all_threads()
    {
        $thread = factory('App\Thread')->create();

        $response = $this->get('/threads');
        $response->assertSee($thread->title);
    }

    /** @test */
    public function a_user_can_browse_a_single_threads()
    {
        $thread = factory('App\Thread')->create();

        $response = $this->get('/threads/' . $thread->id);
        $response->assertSee($thread->title);
    }

    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // given we have a thread
        // and that thread includes replies
        // when we visit a thread page 
        // then we should see the replies
    }
}
