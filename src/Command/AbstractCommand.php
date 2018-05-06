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
    /*
     * Trait
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
     * Output file path.
     *
     * @var string | null
     */
    protected $output;

    /*+
     * Output for full class name.
     *
     * @var string
     */
    public $className;

    /**
     * Output for short class name.
     *
     * @var string
     */
    public $shortName;

    /**
     * Output for namespace.
     *
     * @var string
     */
    public $namespace;

    /**
     * Constructor.
     *
     * @param array  $options
     * @param string $className Target class name
     * @param string $output    Output file path
     */
    public function __construct(string $className, string $output = null)
    {
        $this->target = new ReflectionClass(str_replace('/', '\\', $className));
        $this->output = $output;
    }

    /**
     * Generate command.
     *
     * @return bool true is success to generate.
     */
    public function generate() : bool
    {
        $this->setClassName();
        $functions = '';
        foreach ($this->target->getMethods() as $method) {
            if ($this->isCurrentPublicMethod($method)) {
                $functions .= $this->getFunctions($method);
            }
        }

        $fileName = $this->getOutputFileName($this->target, $this->output);
        $this->output($fileName, $this->bind(static::MAIN_TEMPLATE_NAME, ((array)$this) + compact('functions')));

        return true;
    }

    /**
     * Set class name and namespace name.
     *
     * @return void
     */
    abstract protected function setClassName();

    /**
     * Get functions.
     *
     * @param ReflectionMethod $method Target Method
     *
     * @return string Test Function Value
     */
    abstract protected function getFunctions(ReflectionMethod $method) : string;

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
