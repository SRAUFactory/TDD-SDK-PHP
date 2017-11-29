<?php

namespace Tdd\Command\Traits;

use ReflectionClass;

/**
 * Template Trait
 * This trait is to output to template.
 */
trait TemplateTrait
{
    /**
     * The values bind on template.
     *
     * @param string $templateName Template file name
     * @param array  $values       The values to bind
     *
     * @return string After bind on template value.
     */
    protected function bind($templateName, array $values)
    {
        $bindValues = file_get_contents(dirname(__FILE__)."/../../../templates/{$templateName}.txt");
        foreach ($values as $key => $value) {
            $bindValues = str_replace("###{$key}###", $value, $bindValues);
        }

        return $bindValues;
    }

    /**
     * Output to file.
     *
     * @param string $fileName Output File Name
     * @param string $value    Output value
     *
     * @return void
     */
    protected function output($fileName, $value)
    {
        file_put_contents($fileName, $value);
    }

    /**
     * Get Output File Name.
     *
     * @param ReflectionClass $target  Target Class
     * @param array           $options
     *
     * @return string Ouput File Name
     */
    protected function getOutputFileName(ReflectionClass $target, array $options)
    {
        if (empty($options['output'])) {
            return str_replace('.php', 'Test.php', $target->getFileName());
        }

        return $options['output'].'/'.$target->getShortName().'Test.php';
    }
}
