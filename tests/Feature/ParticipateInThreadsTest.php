<?php

namespace Tests\Feature;

use App\Activity;
use Tests\TestCase;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;

    
    /** @test */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();
        
        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);
        
        $this->post($thread->path() . '/replies', $reply->toArray())
        ->assertSessionHasErrors('body');
    }
    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');
        
        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        
        $this->delete("/replies/{$reply->id}");

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }
}
