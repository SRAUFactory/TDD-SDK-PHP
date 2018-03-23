<?php

namespace Tdd\Traits;

/**
 * Log Trait
 * This trait is to ouput logs.
 */
trait LogTrait
{
    /**
     * Ouput log.
     *
     * @param string $log Log to output
     */
    protected function outputLog(string $log)
    {
        echo $log."\n";
    }
}
