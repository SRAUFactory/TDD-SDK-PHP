<?php
namespace Tdd\Command;
/**
 * Generate Test Case Class
 */
class TestCase extends AbstractCommand {
    /**
     * @override
     * @return boolean true is success to create.
     */ 
    public function create() {
         $outputFunctions = "";
         $methods = $this->target->getMethods();
         foreach ($methods as $method) {
             if (!$this->isOutputMethod($method)) {
                 continue;
             }
 
             // @ToDo Replace to Document.php
             $outputParams = [];
             $outputDocs = "";
             foreach ($method->getParameters() as $parameter) {
                 $name = "$". $parameter->name;
                 $outputDocs .= "\n     * @param string {$name} any param";
                 $outputParams[] = $name;
             }

             $paramValues = [
                 "name" => $method->name,
                 "largeName" => ucfirst($method->name),
                 "docs" => $outputDocs,
                 "params" =>  implode(", ", $outputParams),
             ];
             $templateName = "TestFunction";
         
             if ($method->isStatic()) {
                 $templateName = "TestStaticFunction";
                 $paramValues["className"] = $this->target->getShortName();
             }

             $outputFunctions .= $this->bindTemplate($templateName, $paramValues);
         }

         $values = [
             "className" => $this->target->getName(),
             "shortName" => $this->target->getShortName(),
             "testFunctions" => $outputFunctions,
         ];
         $outputTestCase = $this->bindTemplate("TestCase", $values);
         $this->outputFile($outputTestCase);
         return true;
    }
}
