<?php

namespace TddTest\Command;

use Exception;
use InvalidArgumentException;
use Tdd\Command\SourceCode;
use TddTest\TddTestBase;

/**
 * Test Case for Tdd\Command\SourceCode.
 */
class SourceCodeTest extends TddTestBase
{
    /**
     * Test for create.
     *
     * @dataProvider getProvidorCreate
     *
     * @param string $className Target Class Name
     * @param string $expected  Expected class name
     */
    public function testCreate(string $className, string $expected)
    {
        $dir = getenv(TEST_OUTPUT_DIR);
        $this->target = new SourceCode(['classname' => $className, 'output' => $dir]);
        $actual = $this->target->create();
        $this->assertTrue($actual);
        $this->assertFileExists($dir.'/'.$expected.'.php');
    }

    /**
     * Test Providor for create.
     *
     * @return array The list of Test Parameters
     */
    public function getProvidorCreate() : array
    {
        return [
            ['TddTest/Command/TestCodeTest',     'TestCode'],
            ['TddTest/Runner/CommandRunnerTest', 'CommandRunner'],
        ];
    }

    /**
     * Test case of error for create.
     *
     * @dataProvider getProvidorCreateError
     *
     * @param string    $className Target Class Name
     * @param Exception $expected  Expected Value
     */
    public function testCreateError(string $className, Exception $expected)
    {
        $this->expectException(get_class($expected));
        $this->expectExceptionMessage($expected->getMessage());
        $this->target = new SourceCode(['classname' => $className]);
        $this->target->create();
    }

    /**
     * Test Providor of the case of error for create.
     *
     * @return array The list of Test Parameters
     */
    public function getProvidorCreateError()
    {
        return [
            ['Tdd/Command/TestCode', new InvalidArgumentException('Target class not test class!!')],
        ];
    }
}
