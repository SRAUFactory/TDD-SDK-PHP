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
     * Test for generate.
     *
     * @dataProvider getProvidorGenerate
     *
     * @param string $className Target Class Name
     * @param string $expected  Expected class name
     */
    public function testGenerate(string $className, string $expected)
    {
        $dir = getenv(TEST_OUTPUT_DIR);
        $this->target = new SourceCode($className, $dir);
        $actual = $this->target->generate();
        $this->assertTrue($actual);
        $this->assertFileExists($dir.'/'.$expected.'.php');
    }

    /**
     * Test Providor for generate.
     *
     * @return array The list of Test Parameters
     */
    public function getProvidorGenerate() : array
    {
        return [
            ['TddTest/Command/TestCodeTest',     'TestCode'],
            ['TddTest/Runner/CommandRunnerTest', 'CommandRunner'],
        ];
    }

    /**
     * Test case of error for generate.
     *
     * @dataProvider getProvidorGenerateError
     *
     * @param string    $className Target Class Name
     * @param Exception $expected  Expected Value
     */
    public function testGenerateError(string $className, Exception $expected)
    {
        $this->expectException(get_class($expected));
        $this->expectExceptionMessage($expected->getMessage());
        $this->target = new SourceCode($className);
        $this->target->generate();
    }

    /**
     * Test Providor of the case of error for generate.
     *
     * @return array The list of Test Parameters
     */
    public function getProvidorGenerateError()
    {
        return [
            ['Tdd/Command/TestCode', new InvalidArgumentException('Target class not test class!!')],
        ];
    }
}
