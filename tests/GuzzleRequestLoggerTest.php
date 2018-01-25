<?php

namespace CaseyAMcL\GuzzleRequestLogger;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;


/**
 * Class GuzzleRequestLoggerTest
 * @package CaseyAMcL\GuzzleRequestLogger
 */
class GuzzleRequestLoggerTest extends TestCase
{
    public function testInstantiationSucceeds()
    {
        $obj = new GuzzleRequestLogger(new NullLogger());
        $this->assertInstanceOf(GuzzleRequestLogger::class, $obj);
    }

    // TODO: Add tests for non-seekable response bodies.
}
