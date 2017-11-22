<?php
namespace Tdd\Command\Traits;
use \ReflectionClass;
/**
 * Template Trait
 * This trait is to output to template.
 * @package Tdd\Command\Traits
 */
trait TemplateTrait {
    /**
     * The values bind on template.
     * @param string $templateName Template file name
     * @param array $values The values to bind
     * @return string After bind on template value.
     */ 
    protected function bind($templateName, array $values) {
        $bindValues = file_get_contents(dirname(__FILE__). "/../../../templates/{$templateName}.txt");
        foreach ($values as $key => $value) {
            $bindValues = str_replace("###{$key}###", $value, $bindValues);
        }
        return $bindValues;
    }

    /**
     * Output to file
     * @param string $fileName Output File Name
     * @param string $value Output value
     * @return void
     */
    protected function output($fileName, $value) {
        file_put_contents($fileName, $value);
    }

    /**
     * Get File Name
     * @param ReflectionClass $target Target Class
     * @param array $options
     * @return string Ouput File Name
     */
    protected function getFileName(ReflectionClass $target, array $options) {
        $fileName = str_replace(".php", "Test.php", $target->getFileName());
        if (!empty($options["output"])) {
            $fileName = $options["output"]. "/". $target->getShortName() . "Test.php";
        }
        return $fileName;
    }
}
