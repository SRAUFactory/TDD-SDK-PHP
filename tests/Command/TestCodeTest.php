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
     * Test for create.
     *
     * @dataProvider getProvidorCreate
     *
     * @param string $className Target Class Name
     */
    public function testCreate(string $className)
    {
        $dir = getenv(TEST_OUTPUT_DIR);
        $params = ['classname' => $className, 'output' => $dir];
        $this->target = new TestCode($params);
        $actual = $this->target->create();
        $this->assertTrue($actual);
        $expected = explode('/', $className);
        $this->assertFileExists($dir.'/'.$expected[2].'Test.php');
    }

    /**
     * Test Providor for create.
     *
     * @return array The list of Test Parameters
     */
    public function getProvidorCreate()
    {
        return [
            ['Tdd/Command/TestCode'],
            ['Tdd/Runner/CommandRunner'],
        ];
    }
}
