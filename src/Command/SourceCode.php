<?php

namespace Tdd\Command;

use InvalidArgumentException;
use Tdd\Command\Traits\TemplateTrait;

/**
 *  The class to generate Source Code.
 */
class SourceCode extends AbstractCommand
{
    /*
     * Traits
     */
    use TemplateTrait;

    /**
     * Main Template Name.
     */
    const MAIN_TEMPLATE_NAME = 'Class';

    /**
     * File Ext.
     */
    const FILE_EXT_TARGET = 'Test'.self::DEFAULT_FILE_EXT;
    const FILE_EXT_OUTPUT = self::DEFAULT_FILE_EXT;

    /**
     * @override
     *
     * @see Tdd\Command\AbstractCommand::getOutputValues
     */
    protected function getOutputValues() : array
    {
        $namespaces = preg_replace('/Test$/', '', explode('\\', $this->target->getName()));
        $className = implode('\\', $namespaces);
        $shortName = array_pop($namespaces);
        $namespace = implode('\\', $namespaces);
        $functions = '';

        if ($this->isNotTestClass($className)) {
            throw new InvalidArgumentException('Target class not test class!!');
        }

        /* @var ReflectionMethod $method */
        foreach ($this->target->getMethods() as $method) {
            $methodName = preg_replace('/^test/', '', $method->name);
            if ($this->isCurrentPublicMethod($method) && $methodName !== $method->name) {
                $methodName = lcfirst($methodName);
                echo "{$method->class}::{$method->name} => {$methodName}\n";
            }
        }

        return compact('className', 'shortName', 'namespace', 'functions');
    }

    /**
     * Check if the target class doesn't inherits from PHPUnit\Framework\TestCase.
     *
     * @param string $className Target Class Name
     *
     * @return bool If the target class doesn't inherits from PHPUnit\Framework\TestCase is ture
     */
    private function isNotTestClass(string $className) : bool
    {
        // @ToDo Add check to inherits from PHPUnit\Framework\TestCase
        return $className === $this->target->getName();
    }
}
