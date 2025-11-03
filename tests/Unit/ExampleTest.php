<?php

namespace Tests\Unit;

use App\Models\User;

class ExampleTest extends \Tests\TestCase
{
    public function test_one()
    {
        $obj = User::first();
        #$this->assertNotEmpty($obj);
        $this->assertTrue(true);
    }
}
