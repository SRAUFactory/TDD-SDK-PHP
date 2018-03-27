<?php

namespace Tdd\Runner;

use \ReflectionClass;

/**
 * Command options.
 */
class Options
{
    const KEY_GENERATE = 'generate';
    const KEY_HELP = 'help';
    const KEY_INPUT = 'input';
    const KEY_OUTPUT = 'output';

    /**
     * Options.
     *
     * @var array
     */
    private $options;

    public function set() : self
    {
        $this->options = getopt($this->getShortOptions(), $this->getLongOptions());
        // @ToDo Merge to short options into long options
        return $this;
    }

    private function getShortOptions() : string
    {
        $result = '';
        foreach ($this->getOptionKeys() as $key) {
            $result .= $this->getShortOptionKey($key).$this->getOptionKeyShufix($key);
        }

        return $result;
    }

    private function getLongOptions() : array
    {
        $result = [];
        foreach ($this->getOptionKeys() as $key) {
            $result[] = $key.$this->getOptionKeyShufix($key);
        }

        return $result;
    }

    public function isset(string $key) : bool
    {
        return array_key_exists($key, $this->options)
           || array_key_exists($this->getShortOptionKey($key), $this->options);
    }

    public function get(string $key) : string
    {
        return $this->options[$key] ?? $this->options[$this->getShortOptionKey($key)] ?? '';
    }

    public function getValues() : array
    {
        return $this->options;
    }

    private function getOptionKeyShufix(string $key) : string
    {
        return $this->isArgValueRequired($key) ? ':' : '';
    }

    private function getShortOptionKey(string $key) : string
    {
        return substr($key, 0, 1);
    }

    private function getOptionKeys() : array
    {
        $reflect = new ReflectionClass($this);
        return $reflect->getConstants();
    }

    private function isArgValueRequired(string $key) : bool
    {
        return $key !== self::KEY_HELP;
    }
}
