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

    const HELP_MESSAGES = [
        self::KEY_GENERATE => 'Generate source/test file.',
        self::KEY_HELP     => 'Prints this usage information.',
        self::KEY_INPUT    => 'Import source/test class file path.',
        self::KEY_OUTPUT   => 'Export generated file to directory.',
    ];
    const HELP_VALUE_NAMES = [
        self::KEY_GENERATE => 'source|test',
        self::KEY_INPUT    => "<path>\t",
        self::KEY_OUTPUT   => "<path>\t",
    ];

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
        $this->options = getopt(implode('', $shortOptions), $longOptions);
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
     * Convert values to string.
     *
     * @return string convertd values
     */
    public function __toString() : string
    {
        return json_encode($this->options);
    }

    /**
     * Get help message.
     *
     * @return string help message
     */
    public function getHelpMessage() : string
    {
        $helpMessages = [];
        $format = "  -%s|--%s %s\t%s";
        foreach ($this->getOptionKeys() as $key) {
            $shortOption = $this->getShortOptionKey($key);
            $optionName = self::HELP_VALUE_NAMES[$key] ?? "\t\t";
            $helpMessages[] = sprintf($format, $shortOption, $key, $optionName, self::HELP_MESSAGES[$key]);
        }

        return implode(PHP_EOL, $helpMessages);
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
        return array_filter((new ReflectionClass($this))->getConstants(), function ($key) {
            return gettype($key) === 'string';
        });
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
