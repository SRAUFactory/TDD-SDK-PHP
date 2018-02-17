<?php

namespace TddTest\Command;

use Tdd\Command\SourceCode;
use TddTest\TddTestBase;

/**
 * Test Case for Tdd\Command\SourceCode
 */
class SourceCodeTest extends TddTestBase
{
    /**
     * Test for create.
     *
     * @dataProvider getProvidorCreate
     *
     * @param string $className Target Class Name
     */
    function testCreate(string $className)
    {
        $dir = getenv(TEST_OUTPUT_DIR);
        $this->target = new SourceCode(['classname' => $className, 'output' => $dir]);
        $actual = $this->target->create();
        $this->assertTrue($actual);
        // @ToDo Add asserts for output file exists
    }

    /**
     * Test Providor for create.
     *
     * @return array The list of Test Parameters 
     */
    function getProvidorCreate()
    {
        return [
            ['TddTest/Command/TestCodeTest'],
            ['TddTest/Runner/CommandRunnerTest'],
        ];
    }
}
