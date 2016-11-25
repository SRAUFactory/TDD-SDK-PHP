<?php
namespace TddTest;
use \PHPUnit_Framework_TestCase;
/**
 * The test base class on TDD.
 * To inherit this class when TDD test class implement.
 */ 
class TddTestBase extends PHPUnit_Framework_TestCase {
    /**
     * The instance object to test class
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
}
