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
     * File Ext.
     */
    const FILE_EXT_TARGET = 'Test.php';
    const FILE_EXT_OUTPUT = self::DEFAULT_FILE_EXT;

    /**
     * @override
     *
     * @see Tdd\Command\AbstractCommand::create
     */
    public function create() : bool
    {
        // @ToDo Implement codes
        return true;
    }
}
