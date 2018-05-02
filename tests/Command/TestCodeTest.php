<?php

namespace TddTest\Command;

use Tdd\Command\TestCode;
use TddTest\TddTestBase;

/**
 * Test Case for Tdd\Command\TestCode.
 */
class TestCodeTest extends TddTestBase
{
    /**
     * Test for generate.
     *
     * @dataProvider getProvidorGenerate
     *
     * @param string $className Target Class Name
     */
    public function testGenerate(string $className)
    {
        $dir = getenv(TEST_OUTPUT_DIR);
        $this->target = new TestCode($className, $dir);
        $actual = $this->target->generate();
        $this->assertTrue($actual);
        $expected = explode('/', $className);
        $this->assertFileExists($dir.'/'.$expected[2].'Test.php');
    }

    /**
     * Test Providor for generate.
     *
     * @return array The list of Test Parameters
     */
    public function getProvidorGenerate()
    {
        return [
            ['Tdd/Command/TestCode'],
            ['Tdd/Runner/CommandRunner'],
        ];
    }
}
