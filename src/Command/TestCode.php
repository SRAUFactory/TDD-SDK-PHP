<?php
namespace Tdd\Command;
use Tdd\Command\Traits\TemplateTrait;
use \ReflectionMethod;
use \ReflectionParameter;
/**
 * The class to generate Test Code
 * @package Tdd\Command
 */
class TestCode extends AbstractCommand {
    /**
     * Traits
     */
    use TemplateTrait;
    /**
     * Output Test Functions Value
     * @var string
     */ 
    private $functions = "";
    /**
     * Output Test Parameters
     * @var array 
     */ 
    private $params = [];
    /**
     * Output PHPDoc Value
     * @var string
     */ 
    private $docs = "";

    /**
     * @override
     * @return boolean true is success to create.
     */ 
    public function create() {
        array_walk($this->target->getMethods(), [$this, 'setFunctions']);
        $className = $this->target->getName();
        $shortName = $this->target->getShortName();
        $testFunctions = $this->functions;
        $namespace = $this->target->getNamespaceName();
        $this->output($this->getOutputFileName($this->target, $this->options), $this->bind("TestCase", compact("className", "shortName", "testFunctions", "namespace")));
        return true;
    }

    /**
     * Set test functions
     * @param ReflectionMethod $method Target Method
     * @param int $index The index of Method List
     * @return void
     */ 
    private function setFunctions(ReflectionMethod $method, $index) {
        if ($this->target->getName() !== $method->class || !$method->isPublic()) return;
        array_walk($method->getParameters(), [$this, 'setParameters']); 
        $args = $this->getArgs4BindTemplateByMethodName($method);
        $outputProvider = (count($this->params) > 0)? $this->bind("TestProvider", $args) : "";
        if (!empty($outputProvider)) $args["docs"] = $this->addDataProvider2Docs($args["docs"], $outputProvider);
        $this->functions .= $this->bind("TestFunction", $args). $outputProvider;
    }

    /**
     * Get arguments for bind by method name
     * @param ReflectionMethod
     * @return array arguments
     */
    private function getArgs4BindTemplateByMethodName(ReflectionMethod $method) {
        $largeName = ucfirst($method->name);
        $params = implode(", ", $this->params);
        $className = $this->target->getShortName();
        $callMethod = $method->isStatic()? "$className::{$method->name}" : '$this->target->'. $method->name;
        return compact("largeName", "params", "className", "callMethod") + ["name" => $method->name, "docs" => $this->docs];
    }

    /**
     * Set test parameters
     * @param ReflectionParameter $parameter
     * @param int $index The index of Parameter List
     * @return void
     */
    private function setParameters(ReflectionParameter $parameter, $index) {
        $this->docs .= "\n     * @param string \${$parameter->name} any param";
        $this->params[] =  "$". $parameter->name;
    }

    /**
     * Add data provider to docs
     * @param string $docs Docs
     * @param string $dataProvider Adding data provider
     * @return string Added docs
     */
    private function addDataProvider2Docs($docs, $dataProvider) {
        preg_match('/(function )[a-zA-z0-9:punct:]*/', $dataProvider, $matches);
        $providerName = str_replace($matches[1], "", $matches[0]);
        return "@dataProvider {$providerName}{$docs}";
    }
}
