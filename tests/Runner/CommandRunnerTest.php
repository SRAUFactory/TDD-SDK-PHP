<?php
namespace TddTest\Runner;
use Tdd\Runner\CommandRunner;
use TddTest\TddTestBase;
use \InvalidArgumentException;
/**
 * Test Case for Tdd\Runner\CommandRunner
 * @package TddTest\Runner
 */
class CommandRunnerTest extends TddTestBase
{
    /**
     * Test for main
     * @dataProvider getProvidorRun
     * @param array $argv Argument values
     * @param mixed $excepted
     */ 
    function testMain(array $argv, $excepted)
    {
        try {
            $_SERVER['argv'] = $argv;
            $actual = CommandRunner::main();
            $this->assertSame($excepted, $actual);
        } catch (InvalidArgumentException $e) {
            $this->assertException($excepted, $e);
        }
    }

    /**
     * Test for run
     * @dataProvider getProvidorRun
     * @param array $argv Argument values
     */ 
    function testRun(array $argv, $excepted)
    {
        try {
            $this->target = new CommandRunner();
            $actual = $this->target->run($argv);
            $this->assertSame($excepted, $actual);
        } catch(InvalidArgumentException $e) {
            $this->assertException($excepted, $e);
        }
    }

    /**
     * Test Providor for run
     * @return array The list of Test Parameters
     */
    function getProvidorRun()
    {
        $className = "Tdd/Command/TestCode";
        $output = getenv(TEST_OUTPUT_DIR);
        $ArgumentException = new InvalidArgumentException("Argument is missing.");
        $NoSuchCommandException = new InvalidArgumentException("No such command!!");
        return [
            [
                ["tdd", "create", "test",   "--classname=". $className, "--output=". $output],
                true,
            ],
            [
                ["tdd", "create", "source", "--classname=". $className, "--output=". $output],
                $NoSuchCommandException,
            ],
            [
                ["tdd", "create", "doc",    "--classname=". $className, "--output=". $output],
                $NoSuchCommandException,
            ],
            [
                ["tdd", "create", "help",   "--classname=". $className, "--output=". $output],
                $NoSuchCommandException,
            ],
            [[], $ArgumentException],
            [["tdd", "create"], $ArgumentException],
        ];
    }

    /**
     * Assert Exception
     * @param Exception $excepted
     * @param Exception $actual 
     */
    private function assertException($excepted, InvalidArgumentException $actual)
    {
        $this->assertSame(get_class($excepted), get_class($actual));
        $this->assertSame($excepted->getMessage(), $actual->getMessage());
    }
}
