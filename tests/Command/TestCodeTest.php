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
     * @param array $params 
     */ 
    function testCreate(array $params) {
        $this->target = new TestCode($params);
        $actual = $this->target->create();
        $this->assertTrue($actual);
    }

    /**
     * Test Providor for create
     * @return array The list of Test Parameters
     */
    function getProvidorCreate() {
        $testData = [[
            "bootstrap" => "../autoload.php",
            "classname" => "Tdd\Command\TestCode",
            "output" => "./../templates",
        ]];
        $testDataList = [$testData];


        $testData[0]["classname"] = "Tdd\Runner\CommandRunner";
        $testDataList[] = $testData;
        
        return $testDataList;
    }
}
