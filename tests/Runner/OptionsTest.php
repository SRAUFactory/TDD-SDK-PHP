<?php

namespace TddTest\Runner;

use Tdd\Runner\Options;
use TddTest\TddTestBase;

/**
 * Test Case for Tdd\Runner\Options.
 */
class OptionsTest extends TddTestBase
{
    /**
     * Test for isSetOptions.
     */
    public function testIsSetOptions($key)
    {
        $this->target = new Options();
        $this->assertFalse($this->target->isSetOptions(Options::KEY_GENERATE));
    }

    /**
     * Test for get.
     */
    public function testGet($key)
    {
        $this->target = new Options();
        $this->assertSame('', $this->target->get(Options::KEY_HELP));
    }

    /**
     * Test for __toString
     */
    public function test__toString()
    {
        $this->target = new Options();
        $this->assertSame('[]', (string)$this->target);
    }

    /**
     * Test from getHelpMessage
     */
    public function testGetHelpMessage()
    {
        $expected = file_get_contents(dirname(__FILE__).'/OptionsTest_TestGetHelpMessage_Expected.txt');
        $this->target = new Options();
        $this->assertSame($expected, $this->target->getHelpMessage());
    }
}
