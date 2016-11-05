<?php
use Tdd\Command\TestCase;
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
        $this->target = new TestCase();
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
     */ 
    function testCreate() {
        $actual = $this->target->create();
        // @ToDo set the expected value
        $this->assertNotEmpty($actual);
    }

    /**
     * Test Providor for create
     * @return array The list of Test Parameters
     */
    function getProvidorCreate() {
        // @ToDo set the test parameters
        return [];
    }
}
