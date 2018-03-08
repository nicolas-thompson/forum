<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function only_members_can_add_avatars()
    {
        $this->withExceptionHandling();
        $this->json('POST', '/api/1/users/avatar')->assertStatus(401);
    }

    /** @test */
    function a_valid_avatar_must_be_provided()
    {
        $this->withExceptionHandling()->signIn();
        $this->json('POST', '/api/' . auth()->id() .'/users/avatar', [
            'avatar' => 'not-an-image'
        ])->assertStatus(422);
    }
}