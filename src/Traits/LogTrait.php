<?php

namespace Tdd\Traits;

/**
 * Log Trait
 * This trait is to output logs.
 */
trait LogTrait
{
    /**
     * Output log.
     *
     * @param string $log Log to output
     */
    protected function outputLog(string $log)
    {
        echo $log."\n";
    }
}
