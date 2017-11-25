<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Thread extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function a_thread_has_replies()
    {
        $thread = factory('App\Thread')->create();
        $this->assertInstanceOf('Illuminate\Databse\Collection', $thread->replies);
    }

    /** @test */
    function a_thread_has_a_creator()
    {
        $thread = factory('App\Thread')->create();
        $this->assertInstanceOf('App\User', $thread->creator);        
    }
}
