<?php

namespace TddTest\Command;

use Tdd\Command\Options;
use TddTest\TddTestBase;

/**
 * Test Case for Tdd\Command\Options.
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
     * Test for getValues.
     */
    public function testGetValues()
    {
        $this->target = new Options();
        $this->assertSame([], $this->target->getValues());
    }
}
