<?php

namespace Tdd\Runner;

use InvalidArgumentException;
use Tdd\Command\Options;
use Tdd\Traits\LogTrait;

/**
 * Run command batch
 * When you execute for CLI, this class use.
 */
class CommandRunner
{
    /*
     * Traits
     */
    use LogTrait;

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

        return $runner->run(new Options());
    }

    /**
     * Run command.
     *
     * @param Options $options Arguments for execute
     *
     * @return bool The result to run command
     */
    public function run(Options $options) : bool
    {
        if ($options->isSetOptions(Options::KEY_GENERATE) === $options->isSetOptions(Options::KEY_HELP)) {
            throw new InvalidArgumentException('Argument is missing.');
        }

        $command = self::SUPPORTED_CLASSES[$options->get(Options::KEY_GENERATE)];
        if (empty($command)) {
            throw new InvalidArgumentException('No such command!!');
        }

        $method = $options->isSetOptions(Options::KEY_HELP) ? Options::KEY_HELP : Options::KEY_GENERATE;
        $commandClass = new $command($options->get(Options::KEY_INPUT), $options->get(Options::KEY_OUTPUT));
        $logPrefix = get_class($commandClass).'::'.$method;
        $this->outputLog('Execute statrt '.$logPrefix.' args: '.json_encode($options->getValues()));

        $result = $commandClass->{$method}();
        $this->outputLog('Execute finish '.$logPrefix.' result: '.$result);

        return $result;
    }
}
