<?php

namespace Tdd\Command;

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

        // @ToDo Check if $ className is test class
        // @ToDo Create functions

        $values = compact('className', 'shortName', 'namespace', 'functions');
        var_dump($values);

        return $values; 
    }
}
