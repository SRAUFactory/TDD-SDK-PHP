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
     * @override
     *
     * @see Tdd\Command\AbstractCommand::create
     */
    public function create() : bool
    {
        // @ToDo Implement codes
        return true;
    }

    /**
     * @override
     *
     * @see Tdd\Command\Traits\TemplateTrait::getTemplateDirPath
     */
    protected function getTemplateDirPath() : string
    {
        return 'SourceCode';
    }
}
