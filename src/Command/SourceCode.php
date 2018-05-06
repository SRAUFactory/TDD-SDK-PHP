<?php

namespace Tdd\Command;

use InvalidArgumentException;
use ReflectionMethod;
use ReflectionParameter;

/**
 *  The class to generate Source Code.
 */
class SourceCode extends AbstractCommand
{
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
     * @see Tdd\Command\AbstractCommand::setClassName
     */
    protected function setClassName()
    {
        $namespaces = preg_replace('/Test$/', '', explode('\\', $this->target->getName()));
        $this->className = implode('\\', $namespaces);
        $this->shortName = array_pop($namespaces);
        $namespace = implode('\\', $namespaces);
        $this->namespace = empty($namespace) ? '' : $this->bind('Namespace', compact('namespace'));

        if ($this->isNotTestClass($this->className)) {
            throw new InvalidArgumentException('Target class not test class!!');
        }
    }

    /**
     * @override
     *
     * @see Tdd\Command\AbstractCommand::getFunctions
     */
    protected function getFunctions(ReflectionMethod $method) : string
    {
        $methodName = $this->getMethodName($method);
        $args = ['docs' => '', 'name' => lcfirst($methodName), 'title' => $methodName];
        $args += $this->getArgsAndDocs2Functions($method->getParameters());

        return $this->bind('Function', $args);
    }

    /**
     * @override
     *
     * @see Tdd\Command\AbstractCommand::isIgnoreParameter2Functions
     */
    protected function isIgnoreParameter2Functions(ReflectionParameter $parameter) : bool
    {
        return preg_match('/^expected/', $parameter->name);
    }

    /**
     * @override
     *
     * @see Tdd\Command\AbstractCommand::isCurrentPublicMethod
     */
    protected function isCurrentPublicMethod(ReflectionMethod $method) : bool
    {
        return parent::isCurrentPublicMethod($method) && $this->getMethodName($method) !== $method->name;
    }

    /**
     * Get method name.
     *
     * @param ReflectionMethod $method Target Method
     *
     * @return string method name
     */
    private function getMethodName(ReflectionMethod $method) : string
    {
        return preg_replace('/^test/', '', $method->name);
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
