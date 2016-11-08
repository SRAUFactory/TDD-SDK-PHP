<?php
use Tdd\Runner\CommandRunner;
/**
 * Test Case for Tdd\Runner\CommandRunner
 */
class CommandRunnerTest extends PHPUnit_Framework_TestCase {
    /**
     * The instance object to test class
     * @var Tdd\Runner\CommandRunner
     */
    protected $target;

    /**
     * @override
     * @see 
     */
    public function setUp() {
        parent::setUp();
        $this->target = new CommandRunner();
    }

    /**
     * @override
     * @see
     */
    public function tearDown() {
        unset($this->target);
        parent::tearDown();
    }

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
        $ArgumentException = new Exception("Argument is missing.");
        $NoSuchCommandException = new Exception("No such command!!");
        return [
            [
                ["tdd", "create", "test", "--bootstrap=../autoload.php", "--classname=Tdd\Command\TestCase", "--output=./../templates"],
                true,
            ],
            [
                ["tdd", "create", "source", "--bootstrap=../autoload.php", "--classname=Tdd\Command\TestCase", "--output=./../templates"],
                $NoSuchCommandException,
            ],
            [
                ["tdd", "create", "doc", "--bootstrap=../autoload.php", "--classname=Tdd\Command\TestCase", "--output=./../templates"],
                $NoSuchCommandException,
            ],
            [
                ["tdd", "create", "help", "--bootstrap=../autoload.php", "--classname=Tdd\Command\TestCase", "--output=./../templates"],
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
