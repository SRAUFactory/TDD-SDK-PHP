<?php
namespace Tdd\Command;
/**
 * The base class of TDD command.
 * To inherit this class when TDD command implement.
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
     * Constructor
     * @param array $params
     */ 
    public function __construct(array $params) {
        $this->parseParameters($params);
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
        $bindValues = file_get_contents("template/{$templateName}.txt");
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
        return $this->target->getName() === $method->class
            && $method->isPublic();
    }

    /**
     * Parse parameter values
     * @param array $params
     */
    private function parseParameters(array $params) {
        if (!empty($params["bootstrap"])) {
            require($params["bootstrap"]);
        }

        $this->target = new ReflectionClass($params["classname"]);

        $this->outputFileName = $params["output"]. "/". $this->target->getShortName() . "Test.php";
        if (empty($params["output"])) {
           $this->outputFileName = str_replace(".php", "Test.php", $this->target->getFileName());
        }
    }
}
