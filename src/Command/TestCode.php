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
     * Argument of Test Function Docs Format
     */
    const DOCS_ARGUMENT_FORMAT = self::DOCS_PREFIX."@param mixed \$%s any param";
    /**
     * Data Provider of Test Function Docs Format
     */
    const DATA_PROVIDER_FORMAT = self::DOCS_PREFIX."@dataProvider %s%s";

    /**
     * @override
     *
     * @return bool true is success to create.
     */
    public function create()
    {
        $args = [
            'className' => $this->target->getName(),
            'shortName' => $this->target->getShortName(),
            'namespace' => $this->target->getNamespaceName(), 
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
            'largeName' => ucfirst($method->name),
            'name' => $method->name,
            'docs' => '',
            'callMethod' => '$this->target->'.$method->name,
        ];
        if ($method->isStatic()) {
            $args['callMethod'] = $this->target->getShortName()."::".$args['name'];
         }

        $params = [];
        foreach ($method->getParameters() as $parameter) {
            $args['docs'] .=  sprintf(self::DOCS_ARGUMENT_FORMAT, $parameter->name);
            $params[] = '$'.$parameter->name;
        }
        $args['params'] = implode(', ', $params);

        $dataProvider = '';
        if (count($params) > 0) {
            $dataProvider = $this->bind('TestProvider', $args);
            preg_match('/(function )[a-zA-z0-9:punct:]*/', $dataProvider, $matches);
            $providerName = str_replace($matches[1], '', $matches[0]);
            $args['docs'] = sprintf(self::DATA_PROVIDER_FORMAT, $providerName, $args['docs']);
        }

        return $this->bind('TestFunction', $args).$dataProvider;
    }
}
