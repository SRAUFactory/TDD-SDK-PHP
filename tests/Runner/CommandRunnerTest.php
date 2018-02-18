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
     *
     * @dataProvider getProvidorRun
     *
     * @param array $argv     Argument values
     * @param mixed $expected
     */
    public function testMain(array $argv, $expected)
    {
        try {
            $_SERVER['argv'] = $argv;
            $actual = CommandRunner::main();
            $this->assertSame($expected, $actual);
        } catch (InvalidArgumentException $e) {
            $this->assertException($expected, $e);
        }
    }

    /**
     * Test for run.
     *
     * @dataProvider getProvidorRun
     *
     * @param array $argv Argument values
     */
    public function testRun(array $argv, $expected)
    {
        try {
            $this->target = new CommandRunner();
            $actual = $this->target->run($argv);
            $this->assertSame($expected, $actual);
        } catch (InvalidArgumentException $e) {
            $this->assertException($expected, $e);
        }
    }

    /**
     * Test Providor for run.
     *
     * @return array The list of Test Parameters
     */
    public function getProvidorRun()
    {
        $className = 'Tdd/Command/TestCode';
        $testName = 'TddTest/Runner/CommandRunnerTest';
        $output = getenv(TEST_OUTPUT_DIR);
        $ArgumentException = new InvalidArgumentException('Argument is missing.');

        return [
            [
                ['tdd', 'create', 'test',  '--classname='.$className, '--output='.$output, 'test=test'],
                true,
            ],
            [
                ['tdd', 'create', 'source', '--classname='.$testName, '--output='.$output],
                true,
            ],
            [
                ['tdd', 'create', 'help', '--classname='.$className, '--output='.$output],
                new InvalidArgumentException('No such command!!'),
            ],
            [[], $ArgumentException],
            [['tdd', 'create'], $ArgumentException],
        ];
    }

    /**
     * Assert Exception.
     *
     * @param Exception                $expected
     * @param InvalidArgumentException $actual
     */
    private function assertException(Exception $expected, InvalidArgumentException $actual)
    {
        $this->assertSame(get_class($expected), get_class($actual));
        $this->assertSame($expected->getMessage(), $actual->getMessage());
    }
}
