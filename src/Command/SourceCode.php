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
        // @ToDo Implement codes
        return [];
    }
}
