<?php
namespace Tdd\Command;
/**
 * The class to generate Test Code
 */
class TestCode extends AbstractCommand {
    private $functions = ""; 
    private $params = [];
    private $docs = "";

    /**
     * @override
     * @return boolean true is success to create.
     */ 
    public function create() {
         $methods = $this->target->getMethods();
         array_walk($methods, [$this, 'setFunctions']);
         $values = [
             "className" => $this->target->getName(),
             "shortName" => $this->target->getShortName(),
             "testFunctions" => $this->functions,
         ];
         $this->outputFile($this->bindTemplate("TestCase", $values));
         return true;
    }

    /**
     * Set test functions
     * @param ReflectionMethod $method Target Method
     * @param int $index The index of Method List
     */ 
    private function setFunctions($method, $index) {
         if (!$this->isOutputMethod($method)) {
             return;
         }   
         
         $parameters = $method->getParameters();
         array_walk($parameters, [$this, 'setParameters']); 
         $paramValues = [
             "name" => $method->name,
             "largeName" => ucfirst($method->name),
             "docs" => $this->docs,
             "params" => implode(", ", $this->params),
             "className" => $this->target->getShortName(),
         ];  
         $templateName = ($method->isStatic())? "TestStaticFunction" : "TestFunction";
         
         $outputProvider = "";
         if (count($this->params) > 0) {
             $outputProvider = $this->bindTemplate("TestProvider", $paramValues);
             preg_match('/(function )[a-zA-z0-9:punct:]*/', $outputProvider, $matches);
             $providerName = str_replace($matches[1], "", $matches[0]);
             $paramValues["docs"] = "@dataProvider {$providerName}" . $paramValues["docs"];
         }    
         $this->functions .= $this->bindTemplate($templateName, $paramValues). $outputProvider;
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
}
