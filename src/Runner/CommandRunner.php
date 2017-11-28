<?php
namespace Tdd\Runner;
use \InvalidArgumentException;
/**
 * Run command batch
 * When you execute for CLI, this class use.
 * @package Tdd\Runner
 */
class CommandRunner
{
    /**
     * Supported Classes
     * @var array
     */
    const SUPPORTED_CLASSES = [
        "test" => "Tdd\\Command\\TestCode",
//        "source" => "Tdd\\Command\\SourceCode",
//        "doc" => "Tdd\\Command\\Document",
    ];

    /**
     * main function
     */ 
    public static function main()
    {
        $runner = new static;
        return $runner->run($_SERVER['argv']);
    }

    /**
     * run command
     * @param array $args
     * @return boolean The result to run command
     */ 
    public function run(array $args)
    {
        if (count($args) <= 3) throw new InvalidArgumentException("Argument is missing.");
        $options = [];
        foreach($args as $index => $arg) {
            if ($index <= 2 || strlen($arg) <= 2 || substr($arg, 0, 2) !== '--') continue;
            $argv = explode("=", substr($arg, 2));
            if (count($argv) === 2) $options[$argv[0]] = $argv[1];
        }
 
        $command = self::SUPPORTED_CLASSES[$args[2]];
        if (empty($command)) throw new InvalidArgumentException("No such command!!");
        $commandClass = new $command($options);
        return $commandClass->{$args[1]}();
    }
}
