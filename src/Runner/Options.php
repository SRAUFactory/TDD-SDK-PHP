<?php

namespace Tdd\Runner;

use ReflectionClass;

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
    protected $options;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $shortOptions = $longOptions = [];
        foreach ($this->getOptionKeys() as $key) {
            $shufix = $this->getOptionKeyShufix($key);
            $shortOptions[] = $this->getShortOptionKey($key).$shufix;
            $longOptions[] = $key.$shufix;
        }
        $this->options = getopt(implode('', $shortOption), $longOptions);
    }

    /**
     * Check set value.
     *
     * @param string $key Target
     *
     * @return bool True is to set to options
     */
    public function isSetOptions(string $key) : bool
    {
        return array_key_exists($key, $this->options)
            || array_key_exists($this->getShortOptionKey($key), $this->options);
    }

    /**
     * Get value.
     *
     * @param string $key Target
     *
     * @return string value from options
     */
    public function get(string $key) : string
    {
        return $this->options[$key] ?? $this->options[$this->getShortOptionKey($key)] ?? '';
    }

    /**
     * Get all values.
     *
     * @return array all values
     */
    public function getValues() : array
    {
        return $this->options;
    }

    /**
     * Get option key shufix.
     *
     * @param string $key Target
     *
     * @return string option key shufix(`:`, '')
     */
    protected function getOptionKeyShufix(string $key) : string
    {
        return $this->isArgValueRequired($key) ? ':' : '';
    }

    /**
     * Get short option key.
     *
     * @param string $key Target
     *
     * @return string short option key(ex: self::KEY_GENERATE => 'g')
     */
    protected function getShortOptionKey(string $key) : string
    {
        return substr($key, 0, 1);
    }

    /**
     * Get all supported option keys.
     *
     * @return array all supported option keys
     */
    protected function getOptionKeys() : array
    {
        $reflect = new ReflectionClass($this);

        return $reflect->getConstants();
    }

    /**
     * Check argument value required.
     *
     * @param string $key Target
     *
     * @return bool True is required
     */
    protected function isArgValueRequired(string $key) : bool
    {
        return $key !== self::KEY_HELP;
    }
}
