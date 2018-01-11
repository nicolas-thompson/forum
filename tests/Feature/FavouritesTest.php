<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavouritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_can_not_favourite_anything()
    {

        $this->withExceptionHandling()
            ->post('replies/1/favourites')
            ->assertRedirect('/login');        
    }

    /** @test */
    public function an_authenticated_user_can_favourite_any_reply()
    {
        $this->signIn();
        
        $reply = create('App\Reply');

        $this->post('replies/' . $reply->id . '/favourites');

        $this->assertCount(1, $reply->favourites);
    }
}
