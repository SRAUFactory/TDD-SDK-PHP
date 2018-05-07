<?php

namespace TddTest\Runner;

use Exception;
use InvalidArgumentException;
use Tdd\Runner\CommandRunner;
use Tdd\Runner\Options;
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
        $this->assertFalse(CommandRunner::main());
    }

    /**
     * Test for run.
     *
     * @dataProvider getProvidorRun
     *
     * @param Options $options Arguments for execute
     */
    public function testRun(Options $options)
    {
        $this->target = new CommandRunner();
        $this->assertTrue($this->target->run($options));
    }

    /**
     * Test Providor for run.
     *
     * @return array The list of Test Parameters
     */
    public function getProvidorRun() : array
    {
        $output = getenv(TEST_OUTPUT_DIR);

        return [
            [
                new OptionsMock([
                    OptionsMock::KEY_GENERATE => 'test',
                    OptionsMock::KEY_INPUT    => 'Tdd/Command/TestCode',
                    OptionsMock::KEY_OUTPUT   => $output,
                ]),
            ],
            [
                new OptionsMock([
                    OptionsMock::KEY_GENERATE => 'source',
                    OptionsMock::KEY_INPUT    => 'TddTest/Runner/CommandRunnerTest',
                    OptionsMock::KEY_OUTPUT   => $output,
                ]),
            ],
            [
                new OptionsMock([OptionsMock::KEY_HELP => false]),
            ],
        ];
    }

    /**
     * Test case of throw exception for run.
     *
     * @dataProvider getProvidorRunForThrowException
     *
     * @param Options   $options  Arguments for execute
     * @param Exception $expected Expected exception
     */
    public function testRunForThrowException(Options $options, Exception $expected)
    {
        $this->setExpectException($expected);
        $this->target = new CommandRunner();
        $this->target->run($options);
    }

    /**
     * Test Providor for run.
     *
     * @return array The list of Test Parameters
     */
    public function getProvidorRunForThrowException() : array
    {
        return [
            [new Options(), new InvalidArgumentException('Argument is missing.')],
            [new OptionsMock([OptionsMock::KEY_GENERATE => 'help']), new InvalidArgumentException('No such command!!')],
        ];
    }

    /**
     * Set expect exception.
     *
     * @param Exception $expected Expected exception
     */
    private function setExpectException(Exception $expected)
    {
        $this->expectException(get_class($expected));
        $this->expectExceptionMessage($expected->getMessage());
    }
}
