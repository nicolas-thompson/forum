<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForum extends TestCase
{
    use DatabaseMigrations;
    
    /** @test **/
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have an authenticated User
        $this->be($user = factory('App\User')->create());

        // And an existing thread
        $thread = factory('App\Thread')->create();

        // When the adds a reply to the thread
        $reply = factory('App\Reply')->create(['thread_id' => $thread->id]);     
        $this->post('/threads/'.$thread->id.'/replies', $reply->toArray());

        // Then their reply should be visible on the page
        $this->get($thread->path())->assertSee($reply->body);
    }
}