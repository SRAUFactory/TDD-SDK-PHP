<?php

namespace Tdd\Runner;

/**
 * Command options.
 */
class Options
{
    const SHORT_OPTION_GENERATE = 'g:';
    const SHORT_OPTION_HELP = 'h::';
    const SHORT_OPTION_INPUT = 'i:';
    const SHORT_OPTION_OUTPUT = 'o:';

    const LONG_OPTION_GENERATE = 'generate:';
    const LONG_OPTION_HELP = 'help';
    const LONG_OPTION_INPUT = 'input:';
    const LONG_OPTION_OUTPUT = 'output:';

    const OPTION_SETS = [
        self::SHORT_OPTION_GENERATE => self::LONG_OPTION_GENERATE,
        self::SHORT_OPTION_HELP     => self::LONG_OPTION_HELP,
        self::SHORT_OPTION_INPUT    => self::LONG_OPTION_INPUT,
        self::SHORT_OPTION_OUTPUT   => self::LONG_OPTION_OUTPUT,
    ];

    /**
     * Options.
     *
     * @var array
     */
    private $options;

    public function set() : self
    {
        $this->options = getopt($this->getShortOption(), $this->getLongOptions());
        // @ToDo Merge to short options into long options
        return $this;
    }

    private function getShortOption() : string
    {
        return implode('', array_keys(self::OPTION_SETS));
    }

    private function getLongOptions() : array
    {
        return array_values(self::OPTION_SETS);
    }

    public function isset(string $key) : bool
    {
        return array_key_exists($key, $this->options);
    }

    public function get(string $key) : string
    {
        return $this->options[$key] ?? '';
    }

    public function getValues() : array
    {
        return $this->options;
    }
}
