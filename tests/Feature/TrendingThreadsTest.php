<?php

namespace Tests\Feature;

use App\Trending;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->trending = new Trending();

        Redis::del($this->trending->cachekey());
    }

    /** @test **/
    public function it_increments_a_test_score_each_time_it_is_read()
    {
        
        $this->assertEmpty($this->trending->get());
        $thread = create('App\Thread');
        $this->call('GET', $thread->path());

        $trending = Redis::zrevrange('testing_trending_threads', 0, -1);

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, json_decode($trending[0])->title);

    }
}