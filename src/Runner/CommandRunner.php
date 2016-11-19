<?php
namespace Tdd\Runner;
use Tdd\Command\TestCode;
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

         foreach ($argv as $index => $arg) {
             if (strlen($arg) > 2 && substr($arg, 0, 2) === '--') {
                 $args = explode("=", substr($arg, 2));
                 if (count($args) === 2) {
                     $this->options[$args[0]] = $args[1];
                 }
             } elseif ($index === 1) {
                 $this->target = $arg;
             } elseif ($index === 2) {
                 $this->command = $arg;
             }
         }
    }

    /**
     * Get command class.
     * @return string;
     */ 
    private function getCommandClass() {
        switch ($this->command) {
            case "test" :
                return new TestCode($this->options);
            case "source" :
            //    retrun new SourceCode($this->options);
            case "doc" :
            //    return new Document($this->options);
            default :
                throw new Exception("No such command!!");
        }
    }
}

