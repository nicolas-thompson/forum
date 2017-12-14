<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test **/
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have an authenticated User
        $this->be($user = create('App\User'));

        // And an existing thread
        $thread = create('App\Thread');

        // When the adds a reply to the thread
        $reply = create('App\Reply', ['thread_id' => $thread->id]);     
        $this->post($thread->path() . '/replies', $reply->toArray());

        // Then their reply should be visible on the page
        $this->get($thread->path())->assertSee($reply->body);
    }
}
