<?php
declare(strict_types=1);

namespace Nora\Framework;

use PHPUnit\Framework\TestCase;

class FrameworkTest extends TestCase
{
    public function testIsTrue()
    {
        $this->assertInstanceOf(Framework::class, new Framework());
    }
}
