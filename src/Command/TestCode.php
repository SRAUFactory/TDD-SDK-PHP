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
     * @override
     * @return boolean true is success to create.
     */ 
    public function create() {
        $args = ["className" => $this->target->getName(), "shortName" => $this->target->getShortName(), "namespace" => $this->target->getNamespaceName()];
        $functions = array_map([$this, 'getFunctions'], $this->target->getMethods());
        $args["testFunctions"] = implode("", $functions);
        $this->output($this->getOutputFileName($this->target, $this->options), $this->bind("TestCase", $args));
        return true;
    }

    /**
     * Get test functions
     * @param ReflectionMethod $method Target Method
     * @param int $index The index of Method List
     * @return void
     */ 
    private function getFunctions(ReflectionMethod $method, $index) {
        if ($this->target->getName() !== $method->class || !$method->isPublic()) return;

        $args = ["largeName" => ucfirst($method->name), "name" => $method->name, "docs" => ""];
        $params = [];
        foreach ($method->getParameters() as $parameter) {
            $args["docs"] .= "\n     * @param string \${$parameter->name} any param";
            $params[] =  "$". $parameter->name;
        }       
        $args["params"] = implode(", ", $params);
        $args["callMethod"] = $method->isStatic()? $this->target->getShortName(). "::{$method->name}" : '$this->target->'. $method->name;

        $dataProvider = "";
        if (count($params) > 0) {
            $dataProvider = $this->bind("TestProvider", $args);
            preg_match('/(function )[a-zA-z0-9:punct:]*/', $dataProvider, $matches);
            $providerName = str_replace($matches[1], "", $matches[0]);
            $args["docs"] = "@dataProvider {$providerName}{$args["docs"]}";
        }
        return $this->bind("TestFunction", $args). $dataProvider;
    }
}
