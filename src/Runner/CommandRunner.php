<?php

namespace Tdd\Runner;

use InvalidArgumentException;

/**
 * Run command batch
 * When you execute for CLI, this class use.
 */
class CommandRunner
{
    /**
     * Supported Classes.
     *
     * @var array
     */
    const SUPPORTED_CLASSES = [
        'test'   => 'Tdd\\Command\\TestCode',
        'source' => 'Tdd\\Command\\SourceCode',
    ];

    /**
     * Main function.
     *
     * @return bool Result
     */
    public static function main() : bool
    {
        $runner = new static();

        return $runner->run($_SERVER['argv']);
    }

    /**
     * Run command.
     *
     * @param array $args Arguments for execute
     *
     * @return bool The result to run command
     */
    public function run(array $args) : bool
    {
        if (count($args) <= 3) {
            throw new InvalidArgumentException('Argument is missing.');
        }
        $command = self::SUPPORTED_CLASSES[$args[2]];
        if (empty($command)) {
            throw new InvalidArgumentException('No such command!!');
        }
        $commandClass = new $command($this->getOptions($args));

        return $commandClass->{$args[1]}();
    }

    /**
     * Get options.
     *
     * @param array $args Arguments for execute
     *
     * @return array Optional Values
     */
    private function getOptions(array $args) : array
    {
        unset($args[0], $args[1], $args[2]);
        $options = [];
        foreach ($args as $index => $arg) {
            if (strlen($arg) <= 2 || substr($arg, 0, 2) !== '--') {
                continue;
            }
            $argv = explode('=', substr($arg, 2));
            if (count($argv) === 2) {
                $options[$argv[0]] = $argv[1];
            }
        }

        return $options;
    }
}
