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
        $this->json('POST', '/api/1/users/avatar')->assertStatus(401);
    }
}