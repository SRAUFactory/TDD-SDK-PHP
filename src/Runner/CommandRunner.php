<?php
namespace Tdd\Runner;
use \Exception;
/**
 * Run comannd batch
 * When you execute for CLI, this class use.
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

    /**
     * main function
     */ 
    public static function main() {
         $runner = new static;
         return $runner->run($_SERVER['argv']);
    }

    /**
     * run command
     * @param array $argv
     * @return boolean The result to run command
     */ 
    public function run(array $argv) {
        // Analyze $argv to Command options
        $this->parseArguments($argv);

        // Run each command
        $commandClass = $this->getCommandClass();
        return $commandClass->{$this->target}();
    }
    
    /**
     * Parse arguments
     * @param array $argv the arguments to run command.
     */ 
    private function parseArguments(array $argv) {
         if (count($argv) <= 3) {
             throw new Exception("Argument is missing.");
         }

         $this->command = $argv[1];
         $this->target  = $argv[2];

         unset($argv[0]);
         unset($argv[1]);
         unset($argv[2]);

         $this->options = $argv; 
    }

    /**
     * Get command class.
     * @return string;
     */ 
    private function getCommandClass() {
        switch ($this->command) {
            case "source" :
            //    retrun new SourceCode();
            case "test" :
            //    return new TestCase();
            case "doc" :
            //    return new Document();
            default :
                throw new Exception("No such command!!");
        }
    }
}

