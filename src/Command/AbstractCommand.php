<?php
namespace Tdd\Command;
/**
 * TDD Command Base Class
 * To inherit this class when TDD command implement.
 */
abstract class AbstractCommand {
    /**
     * Create Command
     * @param array $params
     */ 
    abstract public function create(array $params);
}
