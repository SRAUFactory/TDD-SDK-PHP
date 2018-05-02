<?php

namespace TddTest\Runner;

use Tdd\Runner\CommandRunner;
use TddTest\Runner\OptionsMock;
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

    public function testRun()
    {
        $options = new OptionsMock([OptionsMock::KEY_GENERATE => 'mock']);
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('No such command!!');
        CommandRunner::run($options);
    }
}
