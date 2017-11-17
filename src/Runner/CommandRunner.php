<?php
namespace Tdd\Runner;
use \InvalidArgumentException;
/**
 * Run command batch
 * When you execute for CLI, this class use.
 * @package Tdd\Runner
 */
class CommandRunner {
    /**
     * Command Name
     * @value [create]
     * @var string
     */ 
    private $command;

    /**
     * Target Name
     * @value [test/source/doc]
     */ 
    private $target;
    private $options = [];

    const SUPPORTED_CLASSES = [
        "test" => "Tdd\\Command\\TestCode",
//        "source" => "Tdd\\Command\\SourceCode",
//        "doc" => "Tdd\\Command\\Document",
    ];

    /**
     * main function
     */ 
    public static function main() {
        $runner = new static;
        return $runner->run($_SERVER['argv']);
    }

    /**
     * run command
     * @param array $args
     * @return boolean The result to run command
     */ 
    public function run(array $args) {
        $this->parseArguments($args);
        $command = self::SUPPORTED_CLASSES[$this->command];
        if (empty($command)) throw new InvalidArgumentException("No such command!!");
        $commandClass = new $command($this->options);
        return $commandClass->{$this->target}();
    }

    /**
     * Parse arguments
     * @param array $args the arguments to run command.
     * @return void
     */ 
    private function parseArguments(array $args) {
        if (count($args) <= 3) throw new InvalidArgumentException("Argument is missing.");
        $this->target = $args[1];
        $this->command = $args[2];
        unset($args[0], $args[1], $args[2]);
        array_walk($args, [$this, "setOption"]);
    }

    /**
     * Set option
     * @param $arg $argument
     * @return void
     */
    private function setOption($arg) {
        if (strlen($arg) <= 2 || substr($arg, 0, 2) !== '--') return;
        $args = explode("=", substr($arg, 2));
        if (count($args) === 2) $this->options[$args[0]] = $args[1];
    }
}
