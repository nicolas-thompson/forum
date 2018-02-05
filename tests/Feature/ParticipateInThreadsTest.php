<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test **/
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have an authenticated User
        $this->be($user = create('App\User'));

        // And an existing thread
        $thread = create('App\Thread');

        // When the user adds a reply to the thread
        $reply = create('App\Reply', ['thread_id' => $thread->id]);     

        $this->post($thread->path() . '/replies', $reply->toArray());

        // Then their reply should be in the database
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);

    }

    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        
        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $upDatedReply = 'You been chnaged, fool.';
        $this->patch("/replies/{$reply->id}", ['body' => $upDatedReply]);
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $upDatedReply]);
    }

    /** @test */
    function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');
    }
}
