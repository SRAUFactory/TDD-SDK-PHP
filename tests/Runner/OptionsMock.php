<?php

namespace TddTest\Runner;

use Tdd\Runner\Options;

/**
 * Command options mock for `TddTest\Runner\CommandRunner`.
 */
class OptionsMock extends Options
{
    /**
     * Constructor.
     */
    public function __construct(array $mockParams)
    {
        $this->options = $mockParams;
    }
}
