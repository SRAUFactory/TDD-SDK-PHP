<?php
namespace TddTest\Command;
use Tdd\Command\TestCase;
use \PHPUnit_Framework_TestCase;
/**
 * Test Case for Tdd\Command\TestCase
 */
class TestCaseTest extends PHPUnit_Framework_TestCase {
    /**
     * The instance object to test class
     * @var Tdd\Command\TestCase
     */
    protected $target;

    /**
     * @override
     * @see 
     */
    public function setUp() {
        parent::setUp();
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
     * Test for create
     * @dataProvider getProvidorCreate
     * @param array $params 
     */ 
    function testCreate(array $params) {
        $this->target = new TestCase($params);
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
            "classname" => "Tdd\Command\TestCase",
            "output" => "./../templates",
        ]];
        $testDataList = [$testData];


        $testData[0]["classname"] = "Tdd\Runner\CommandRunner";
        $testDataList[] = $testData;
        
        return $testDataList;
    }
}
