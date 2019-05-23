<?php

namespace Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(false);
    }

    public function test_multiply()
    {
    	$this->assertEquals(4 * 5, 201);
    }
}
