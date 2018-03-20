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
     * Data Provider of Test Function Docs Format.
     */
    const DATA_PROVIDER_FORMAT = self::DOCS_PREFIX.'@dataProvider %s%s';
    /**
     * Format Call Method.
     */
    const FORMAT_CALL_METHOD = '$this->target->';
    /**
     * Format Namespace
     */
    const FORMAT_NAMESPACE = 'namespace %s;';

    /**
     * Main Template Name.
     */
    const MAIN_TEMPLATE_NAME = 'TestCase';

    /**
     * File Ext.
     */
    const FILE_EXT_TARGET = self::DEFAULT_FILE_EXT;
    const FILE_EXT_OUTPUT = 'Test'.self::DEFAULT_FILE_EXT;

    /**
     * @override
     *
     * @see Tdd\Command\AbstractCommand::getOutputValues
     */
    protected function getOutputValues() : array
    {
        $className = $this->target->getName();
        $shortName = $this->target->getShortName();
        $namespace = str_replace('\\'.$shortName, '', $className);
        $namespace = empty($namespace)? '' : sprintf(self::FORMAT_NAMESPACE, $namespace);

        $testFunctions = '';
        foreach ($this->target->getMethods() as $method) {
            if ($this->isCurrentPublicMethod($method)) {
                $testFunctions .= $this->getFunctions($method);
            }
        }

        return compact('className', 'namespace', 'shortName', 'testFunctions');
    }

    /**
     * Get test functions.
     *
     * @param ReflectionMethod $method Target Method
     *
     * @return string Test Function Values
     */
    private function getFunctions(ReflectionMethod $method) : string
    {
        $args = [
            'name'       => $method->name,
            'largeName'  => ucfirst($method->name),
            'callMethod' => self::FORMAT_CALL_METHOD.$method->name,
            'docs'       => '',
        ];
        if ($method->isStatic()) {
            $args['callMethod'] = $this->target->getShortName().'::'.$method->name;
        }

        $params = [];
        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType() ?? self::TYPE_UNKNOWN;
            $args['docs'] .= sprintf(self::DOCS_ARGUMENT_FORMAT, $type, $parameter->name);
            $params[] = '$'.$parameter->name;
        }
        $args['params'] = implode(', ', $params);

        $dataProvider = (count($params) > 0) ? $this->bind('TestProvider', $args) : '';
        $args['docs'] = $this->setDataProvider2PhpDocs($args['docs'], $dataProvider);

        return $this->bind('TestFunction', $args).$dataProvider;
    }

    /**
     * Set dataProvider to PHP Docs.
     *
     * @param string $docs         Traget PHP Docs
     * @param string $dataProvider Data Provider Values
     *
     * @return string PHPDocs after setting
     */
    private function setDataProvider2PhpDocs(string $docs, $dataProvider) : string
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
