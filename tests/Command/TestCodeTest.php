<?php
namespace TddTest\Command;
use Tdd\Command\TestCode;
use TddTest\TddTestBase;
/**
 * Test Case for Tdd\Command\TestCode
 */
class TestCodeTest extends TddTestBase {
    /**
     * Test for create
     * @dataProvider getProvidorCreate
     * @param string $className Target Class Name 
     */ 
    function testCreate($className) {
        $params = ["classname" => $className, "output" => getenv(TEST_OUTPUT_DIR)];
        $this->target = new TestCode($params);
        $actual = $this->target->create();
        $this->assertTrue($actual);
    }

    /**
     * Test Providor for create
     * @return array The list of Test Parameters
     */
    function getProvidorCreate() {
        return [
            ["Tdd\Command\TestCode"],
            ["Tdd\Runner\CommandRunner"],
        ];
    }
}
