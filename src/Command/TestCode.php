<?php
namespace Tdd\Command;
/**
 * The class to generate Test Code
 */
class TestCode extends AbstractCommand {
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
        $this->outputFile($this->bindTemplate("TestCase", compact("className", "shortName", "testFunctions")));
        return true;
    }

    /**
     * Set test functions
     * @param ReflectionMethod $method Target Method
     * @param int $index The index of Method List
     */ 
    private function setFunctions($method, $index) {
        if (!$this->isOutputMethod($method)) return;
        array_walk($method->getParameters(), [$this, 'setParameters']); 
        $args = $this->getArgs4BindTemplateByMethodName($method->name);
        $outputProvider = (count($this->params) > 0)? $this->bindTemplate("TestProvider", $args) : "";
        if (!empty($outputProvider)) $args["docs"] = $this->addDataProvider2Docs($args["docs"], $outputProvider);
        $templateName = ($method->isStatic())? "TestStaticFunction" : "TestFunction";
        $this->functions .= $this->bindTemplate($templateName, $args). $outputProvider;
    }

    /**
     * Get arguments for bindTemplate by method name
     * @param string $name Method Name
     * @return array arguments
     */
    function getArgs4BindTemplateByMethodName($name) {
        $largeName = ucfirst($name);
        $params = implode(", ", $this->params);
        $className = $this->target->getShortName();
        return compact("name", "largeName", "params", "className") + ["docs" => $this->docs];
    }

    /**
     * Set test parameters
     * @param ReflectionParameter $parameter
     * @param int $index The index of Parameter List
     */
    private function setParameters($parameter, $index) {
        $name = "$". $parameter->name;
        $this->docs .= "\n     * @param string {$name} any param";
        $this->params[] = $name;
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
