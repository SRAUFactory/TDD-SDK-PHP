<?php
namespace Tdd\Command;
/**
 * The class to generate Test Code
 */
class TestCode extends AbstractCommand {
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
                 "params" => implode(", ", $outputParams),
                 "className" => $this->target->getShortName(),
             ];
             $templateName = ($method->isStatic())? "TestStaticFunction" : "TestFunction";

             $outputProvider = "";
             if (count($outputParams) > 0) {
                 $outputProvider = $this->bindTemplate("TestProvider", $paramValues);
                 preg_match('/(function )[a-zA-z0-9:punct:]*/', $outputProvider, $matches);
                 $providerName = str_replace($matches[1], "", $matches[0]);
                 $paramValues["docs"] = "@dataProvider {$providerName}" . $paramValues["docs"];
             }

             $outputFunctions .= $this->bindTemplate($templateName, $paramValues);
             $outputFunctions .= $outputProvider;
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
