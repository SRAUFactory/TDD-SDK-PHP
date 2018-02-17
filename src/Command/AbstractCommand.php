<?php

namespace Tdd\Command;

use ReflectionClass;

/**
 * The base class of TDD command.
 * To inherit this class when TDD command implement.
 */
abstract class AbstractCommand
{
    /**
     * Default File Ext.
     */
    const DEFAULT_FILE_EXT = '.php';

    /**
     * Target class.
     *
     * @var ReflectionClass
     */
    protected $target;
    /**
     * @ToDo Temporary solution
     */
    protected $options = [];

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->target = new ReflectionClass(str_replace('/', '\\', $options['classname']));
        $this->options = $options;
    }

    /**
     * Create command.
     *
     * @return bool true is success to create.
     */
    public function create() : bool
    {
        $fileName = $this->getOutputFileName($this->target, $this->options);
        $this->output($fileName, $this->bind(static::MAIN_TEMPLATE_NAME, $this->getOutputValues()));

        return true;
    }

    /**
     * Get output values.
     *
     * @return array Output Values
     */
    abstract protected function getOutputValues() : array;
}
