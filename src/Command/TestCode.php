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
            $args['testFunctions'] .= $this->getFunctions($method);
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
        if ($this->target->getName() !== $method->class || !$method->isPublic()) {
            return;
        }

        $args = [
            'largeName' => ucfirst($method->name),
            'name' => $method->name,
            'docs' => '',
            'callMethod' => '$this->target->'.$method->name,
        ];
        if ($method->isStatic()) {
            $args['callMethod'] = $this->target->getShortName()."::{$method->name}";
         }

        $params = [];
        foreach ($method->getParameters() as $parameter) {
            $args['docs'] .= self::DOCS_PREFIX."@param string \${$parameter->name} any param";
            $params[] = '$'.$parameter->name;
        }
        $args['params'] = implode(', ', $params);

        $dataProvider = '';
        if (count($params) > 0) {
            $dataProvider = $this->bind('TestProvider', $args);
            preg_match('/(function )[a-zA-z0-9:punct:]*/', $dataProvider, $matches);
            $providerName = str_replace($matches[1], '', $matches[0]);
            $args['docs'] = self::DOCS_PREFIX."@dataProvider {$providerName}{$args['docs']}";
        }

        return $this->bind('TestFunction', $args).$dataProvider;
    }
}
