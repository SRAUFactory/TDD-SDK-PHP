<?php
namespace Tdd\Runner;
class CommandRunner {
    /**
     * main function
     */ 
    public static function main() {
         $runner = new static;
         echo get_class($runner). "\n";
         return $runner->run($_SERVER['argv']);
    }

    /**
     * run command
     * @param array $argv
     * @return 
     */ 
    public function run(array $argv) {
        // @ToDo Run each command
        return true;
    }
}

