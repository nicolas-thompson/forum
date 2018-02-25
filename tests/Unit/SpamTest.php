<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    function it_checks_for_invalid_keywords()
    {
        // Invalid keywords
        
        $spam = new Spam();
        
        $this->assertFalse($spam->detect('Innocent reply here.'));
        
        $this->expectException('Exception');
        
        $spam->detect('yahoo customer support');
    }
    
    /** @test */
    function it_check_for_any_key_being_held_down()
    {
        // Key held down
        $spam = new Spam();
        $this->expectException('Exception');        
        $spam->detect('Hello world aaaaaaaaaaa');
        
    }
}