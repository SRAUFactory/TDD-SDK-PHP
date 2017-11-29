<?php

namespace Tdd\Command;

use ReflectionMethod;
use Tdd\Command\Traits\TemplateTrait;

/**
 * The class to generate Test Code.
 */
class TestCode extends AbstractCommand
{
    /*
     * Traits
     */
    use TemplateTrait;

    /**
     * PHPDocs Prefix.
     */
    const DOCS_PREFIX = "\n     * ";
    /**
     * Argument of Test Function Docs Format.
     */
    const DOCS_ARGUMENT_FORMAT = self::DOCS_PREFIX.'@param mixed $%s any param';
    /**
     * Data Provider of Test Function Docs Format.
     */
    const DATA_PROVIDER_FORMAT = self::DOCS_PREFIX.'@dataProvider %s%s';
    /**
     * Format Call Method 
     */
    const FORMAT_CALL_METHOD = '$this->target-';

    /**
     * @override
     *
     * @return bool true is success to create.
     */
    public function create()
    {
        $fileName = $this->getOutputFileName($this->target, $this->options);
        $bindValues = $this->bind('TestCase', $this->getArgumentsOfBind4TestCase());
        $this->output($fileName, $bindValues);

        return true;
    }

    /**
     * Get Arguments of Bind for Test Case.
     *
     * @return array Arguments of Bind for Test Case
     */
    private function getArgumentsOfBind4TestCase()
    {
        $className = $this->target->getName();
        $shortName = $this->target->getShortName();
        $testFunctions = '';
        foreach ($this->target->getMethods() as $method) {
            if ($className === $method->class && $method->isPublic()) {
                $testFunctions .= $this->getFunctions($method);
            }
        }

        return compact('className', 'shortName', 'testFunctions');
    }

    /**
     * Get test functions.
     *
     * @param ReflectionMethod $method Target Method
     *
     * @return string Test Function Values
     */
    private function getFunctions(ReflectionMethod $method)
    {
        $args = $this->getArgumentsOfBind4TestFunction($method);
        $args = $this->setParams2PhpDocs($args, $method->getParameters());
        $dataProvider = (!empty($args['params'])) ? $this->bind('TestProvider', $args) : '';
        $args['docs'] = $this->setDataProvider2PhpDocs($args['docs'], $dataProvider);

        return $this->bind('TestFunction', $args).$dataProvider;
    }

    /**
     * Get Arguments of Bind for Test Function.
     *
     * @param ReflectionMethod $method Target Method
     *
     * @return array Arguments of Bind for Test Function
     */
    private function getArgumentsOfBind4TestFunction(ReflectionMethod $method)
    {
        $largeName = ucfirst($method->name);
        $callMethod = self::FORMAT_CALL_METHOD.$method->name;
        if ($method->isStatic()) {
            $callMethod = $this->target->getShortName().'::'.$method->name;
        }

        return ['name' => $method->name] + compact('largeName', 'callMethod');
    }

    /**
     * Set Params to PHP Docs.
     *
     * @param array $args       Arguments for Bind to TestFunction
     * @param array $parameters The list of ReflectionParameter
     *
     * @return array Arguments after setting
     */
    private function setParams2PhpDocs(array $args, array $parameters)
    {
        $params = [];
        $args['docs'] = '';
        foreach ($parameters as $parameter) {
            $args['docs'] .= sprintf(self::DOCS_ARGUMENT_FORMAT, $parameter->name);
            $params[] = '$'.$parameter->name;
        }

        return $args + ['params' => implode(', ', $params)];
    }

    /**
     * Set dataProvider to PHP Docs.
     *
     * @param string $docs         Traget PHP Docs
     * @param string $dataProvider Data Provider Values
     *
     * @return string PHPDocs after setting
     */
    private function setDataProvider2PhpDocs($docs, $dataProvider)
    {
        preg_match('/(function )[a-zA-z0-9:punct:]*/', $dataProvider, $matches);
        if (count($matches) >= 2) {
            $providerName = str_replace($matches[1], '', $matches[0]);
            $format = self::DOCS_PREFIX.self::DATA_PROVIDER_FORMAT;
            $docs = sprintf($format, $providerName, self::DOCS_PREFIX.$docs);
        }

        return $docs;
    }
}
