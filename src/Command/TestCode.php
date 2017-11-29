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
     * @override
     *
     * @return bool true is success to create.
     */
    public function create()
    {
        $args = [
            'className'     => $this->target->getName(),
            'shortName'     => $this->target->getShortName(),
            'testFunctions' => '',
        ];
        foreach ($this->target->getMethods() as $method) {
            if ($args['className'] === $method->class && $method->isPublic()) {
                $args['testFunctions'] .= $this->getFunctions($method);
            }
        }

        $fileName = $this->getOutputFileName($this->target, $this->options);
        $bindValues = $this->bind('TestCase', $args);
        $this->output($fileName, $bindValues);

        return true;
    }

    /**
     * Get test functions.
     *
     * @param ReflectionMethod $method Target Method
     *
     * @return void
     */
    private function getFunctions(ReflectionMethod $method)
    {
        $args = [
            'largeName'  => ucfirst($method->name),
            'name'       => $method->name,
            'docs'       => '',
            'callMethod' => '$this->target->'.$method->name,
        ];
        if ($method->isStatic()) {
            $args['callMethod'] = $this->target->getShortName().'::'.$args['name'];
        }

        $args = $this->setParams2PhpDocs($args, $method->getParameters());
        $dataProvider = (!empty($args['params'])) ? $this->bind('TestProvider', $args) : '';
        $args['docs'] = $this->setDataProvider2PhpDocs($args['docs'], $dataProvider);

        return $this->bind('TestFunction', $args).$dataProvider;
    }

    /**
     * Set Params to PHP Docs.
     *
     * @param array $args       Arguments for bind to TestFunction
     * @param array $parameters list of ReflectionParameter
     *
     * @return array Arguments after setting
     */
    private function setParams2PhpDocs(array $args, array $parameters) {
        $params = [];
        foreach ($parameters as $parameter) {
            $args['docs'] .= sprintf(self::DOCS_ARGUMENT_FORMAT, $parameter->name);
            $params[] = '$'.$parameter->name;
        }
        $args['params'] = implode(', ', $params);
        
        return $args;
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
