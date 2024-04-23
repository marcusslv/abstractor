<?php

namespace Braip\Abstracts\Tests\Unit;

use Braip\Abstracts\Tests\TestCase;
use Braip\Abstracts\Tests\User;

class InitialTest extends TestCase
{
    public function test_trait(): void
    {
        //Get User fields
        $result = User::methodTest();
        $this->assertSame($result, 'this ok');
    }
}
