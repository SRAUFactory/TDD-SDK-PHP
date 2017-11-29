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
    abstract public function create();
}
