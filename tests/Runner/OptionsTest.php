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
     * Test for set.
     */
    public function testSet()
    {
        $this->target = new Options();
        $this->assertInstanceOf('Tdd\Runner\Options', $this->target->set());
    }

    /**
     * Test for isSetOptions.
     */
    public function testIsSetOptions($key)
    {
        $this->target = new Options();
        $this->assertFalse($this->target->set()->isSetOptions(Options::KEY_GENERATE));
    }

    /**
     * Test for get.
     */
    public function testGet($key)
    {
        $this->target = new Options();
        $this->assertSame('', $this->target->set()->get(Options::KEY_HELP));
    }

    /**
     * Test for getValues.
     */
    public function testGetValues()
    {
        $this->target = new Options();
        $this->assertSame([], $this->target->set()->getValues());
    }
}
