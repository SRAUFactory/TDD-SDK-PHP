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
    protected function bind(string $templateName, array $values) : string
    {
        $dirPath = basename(strtr(get_class($this), '\\', '/'));
        $filePath = dirname(__FILE__)."/../../../templates/{$dirPath}/{$templateName}.txt";
        $bindValues = file_get_contents($filePath);

        foreach ($values as $key => $value) {
            $bindValues = str_replace("###{$key}###", $value, $bindValues);
        }

        return $bindValues;
    }

    /**
     * Output to file.
     *
     * @param string $fileName Output file name
     * @param string $value    Output value
     *
     * @return void
     */
    protected function output(string $fileName, string $value)
    {
        file_put_contents($fileName, $value);
        $this->outputLog("Output: $fileName");
    }

    /**
     * Get Output File Name.
     *
     * @param ReflectionClass $target Target class
     * @param string          $output Output file path
     *
     * @return string Output File Name
     */
    protected function getOutputFileName(ReflectionClass $target, string $output = null) : string
    {
        $fileName = $output ? $output.'/'.$target->getShortName().self::DEFAULT_FILE_EXT : $target->getFileName();

        return str_replace(static::FILE_EXT_TARGET, static::FILE_EXT_OUTPUT, $fileName);
    }
}
