<?php

namespace Tdd\Command;

use ReflectionClass;
use ReflectionMethod;
use Tdd\Command\Traits\TemplateTrait;
use Tdd\Traits\LogTrait;

/**
 * The base class of TDD command.
 * To inherit this class when TDD command implement.
 */
abstract class AbstractCommand
{
    /**
     * Trait.
     */
    use LogTrait, TemplateTrait;

    /**
     * Default File Ext.
     */
    const DEFAULT_FILE_EXT = '.php';

    /**
     * PHPDocs Prefix.
     */
    const DOCS_PREFIX = "\n     * ";
    /**
     * Argument of Test Function Docs Format.
     */
    const DOCS_ARGUMENT_FORMAT = self::DOCS_PREFIX.'@param %s $%s any param';
    /**
     * Type Unknown.
     */
    const TYPE_UNKNOWN = 'mixed';

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

    /**
     * Check public method in current target class.
     *
     * @param ReflectionMethod $method Target Method
     *
     * @return bool True is public method in current target class
     */
    protected function isCurrentPublicMethod(ReflectionMethod $method) : bool
    {
        return $this->target->getName() === $method->class && $method->isPublic();
    }
}
