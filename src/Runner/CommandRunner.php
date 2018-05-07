<?php

namespace Tdd\Runner;

use Exception;
use InvalidArgumentException;
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
        $options = new Options();
        $runner = new static();
        $result = false;

        try {
            $result = $runner->run($options);
        } catch (Exception $e) {
            $runner->displayHelp($options);
        }

        return $result;
    }

    /**
     * Run process.
     *
     * @param Options $options Arguments for execute
     *
     * @return bool The result to run process
     */
    public function run(Options $options) : bool
    {
        if ($options->isSetOptions(Options::KEY_GENERATE) === $options->isSetOptions(Options::KEY_HELP)) {
            throw new InvalidArgumentException('Argument is missing.');
        }
        if ($options->isSetOptions(Options::KEY_HELP)) {
            return $this->displayHelp($options);
        }

        return $this->runCommand($options);
    }

    /**
     * Run command.
     *
     * @param Options $options Arguments for execute
     *
     * @return bool The result to run command
     */
    private function runCommand(Options $options) : bool
    {
        $command = self::SUPPORTED_CLASSES[$options->get(Options::KEY_GENERATE)];
        if (empty($command)) {
            throw new InvalidArgumentException('No such command!!');
        }

        $commandClass = new $command($options->get(Options::KEY_INPUT), $options->get(Options::KEY_OUTPUT));
        $logPrefix = get_class($commandClass).'::'.Options::KEY_GENERATE;
        $this->outputLog('Execute statrt '.$logPrefix.' args: '.json_encode($options->getValues()));
        $result = $commandClass->{Options::KEY_GENERATE}();
        $this->outputLog('Execute finish '.$logPrefix.' result: '.$result);

        return $result;
    }

    /**
     * Display help.
     *
     * @param Options $options Arguments for execute
     *
     * @return bool The result to display help
     */
    private function displayHelp(Options $options) : bool
    {
        // @ToDo Display options's help message

        return true;
    }
}
