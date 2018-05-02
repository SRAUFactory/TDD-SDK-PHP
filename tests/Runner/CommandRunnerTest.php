<?php

namespace TddTest\Runner;

use Exception;
use InvalidArgumentException;
use Tdd\Runner\CommandRunner;
use TddTest\TddTestBase;

/**
 * Test Case for Tdd\Runner\CommandRunner.
 */
class CommandRunnerTest extends TddTestBase
{
    /**
     * Test for main.
     */
    public function testMain()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Argument is missing.');
        CommandRunner::main();
    }
}
