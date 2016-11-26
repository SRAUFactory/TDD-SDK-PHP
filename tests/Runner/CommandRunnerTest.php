<?php
namespace TddTest\Runner;
use Tdd\Runner\CommandRunner;
use TddTest\TddTestBase;
use \Exception;
/**
 * Test Case for Tdd\Runner\CommandRunner
 */
class CommandRunnerTest extends TddTestBase {
    /**
     * Test for main
     * @dataProvider getProvidorRun
     * @param array $argv Argument values
     * @param mixed $excepted
     */ 
    function testMain(array $argv, $excepted) {
        try {
            $_SERVER['argv'] = $argv;
            $actual = CommandRunner::main();
            $this->assertSame($excepted, $actual);
        } catch (Exception $e) {
            $this->assertException($excepted, $e);
        }
    }

    /**
     * Test for run
     * @dataProvider getProvidorRun
     * @param array $argv Argument values
     */ 
    function testRun(array $argv, $excepted) {
        try {
            $this->target = new CommandRunner();
            $actual = $this->target->run($argv);
            $this->assertSame($excepted, $actual);
        } catch(Exception $e) {
            $this->assertException($excepted, $e);
        }
    }

    /**
     * Test Providor for run
     * @return array The list of Test Parameters
     */
    function getProvidorRun() {
        $bootstrap = getenv(TEST_BOOTSTRAP);
        $className = "Tdd\Command\TestCode";
        $output = getenv(TEST_OUTPUT_DIR);
        $ArgumentException = new Exception("Argument is missing.");
        $NoSuchCommandException = new Exception("No such command!!");
        return [
            [
                ["tdd", "create", "test",   "--bootstrap=". $bootstrap, "--classname=". $className, "--output=". $output],
                true,
            ],
            [
                ["tdd", "create", "source", "--bootstrap=". $bootstrap, "--classname=". $className, "--output=". $output],
                $NoSuchCommandException,
            ],
            [
                ["tdd", "create", "doc",    "--bootstrap=". $bootstrap, "--classname=". $className, "--output=". $output],
                $NoSuchCommandException,
            ],
            [
                ["tdd", "create", "help",   "--bootstrap=". $bootstrap, "--classname=". $className, "--output=". $output],
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
    private function assertException($excepted, Exception $actual) {
        $this->assertSame(get_class($excepted), get_class($actual));
        $this->assertSame($excepted->getMessage(), $actual->getMessage());
    }
}
