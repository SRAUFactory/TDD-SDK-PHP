<?php
namespace Tdd\Command;
use \ReflectionClass;
use \ReflectionMethod;
/**
 * The base class of TDD command.
 * To inherit this class when TDD command implement.
 * @package Tdd\Command
 */
abstract class AbstractCommand {
    /**
     * Target class
     * @var ReflectionClass
     */
    protected $target;
    /**
     * Command output file name
     * @var string
     */ 
    protected $outputFileName;
    /**
     * @ToDo Temporary solution
     */
    protected $options = [];

    /**
     * Constructor
     * @param array $options
     */ 
    public function __construct(array $options) {
        $this->parseOptions($options);
        $this->options = $options;
    }

    /**
     * Create command
     * @return boolean true is success to create.
     */ 
    abstract public function create();

    /**
     * The values bind on template.
     * @param string $templateName Template file name
     * @param array $values The values to bind
     * @return string After bind on template value.
     */ 
    protected function bindTemplate($templateName, array $values) {
        $bindValues = file_get_contents(dirname(__FILE__). "/../../templates/{$templateName}.txt");
        foreach ($values as $key => $value) {
            $bindValues = str_replace("###{$key}###", $value, $bindValues);
        }
        return $bindValues;
    }

    /**
     * Output file
     * @param string $value Output value
     */ 
    protected function outputFile($value) {
        file_put_contents($this->outputFileName, $value);
    }

    /**
     * Is output method
     * @param ReflectionMethod $method Target method
     * @return boolean True is output method 
     */ 
    protected function isOutputMethod(ReflectionMethod $method) {
        return $this->target->getName() === $method->class && $method->isPublic();
    }

    /**
     * Parse parameter values
     * @param array $options
     */
    private function parseOptions(array $options) {
        $this->target = new ReflectionClass(str_replace("/", "\\", $options['classname']));
        $this->outputFileName = str_replace(".php", "Test.php", $this->target->getFileName());
        if (!empty($options["output"])) {
            $this->outputFileName = $options["output"]. "/". $this->target->getShortName() . "Test.php";
        }
    }
}
