<?php

namespace Tdd\Command;

use ReflectionMethod;
use ReflectionParameter;

/**
 * The class to generate Test Code.
 */
class TestCode extends AbstractCommand
{
    /**
     * Data Provider of Test Function Docs Format.
     */
    const FORMAT_DATA_PROVIDER = self::DOCS_PREFIX.'@dataProvider %s%s';
    /**
     * Format Call Method.
     */
    const FORMAT_CALL_METHOD = '$this->target->';
    /**
     * Format Namespace.
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
     * @see Tdd\Command\AbstractCommand::setClassName
     */
    protected function setClassName()
    {
        $this->className = $this->target->getName();
        $this->shortName = $this->target->getShortName();
        $this->namespace = str_replace('\\'.$this->shortName, '', $this->className);
        $this->namespace = empty($this->namespace) ? '' : sprintf(self::FORMAT_NAMESPACE, $this->namespace);
    }

    /**
     * @override
     *
     * @see Tdd\Command\AbstractCommand::getFunctions
     */
    protected function getFunctions(ReflectionMethod $method) : string
    {
        $args = [
            'name'       => $method->name,
            'largeName'  => ucfirst($method->name),
            'callMethod' => self::FORMAT_CALL_METHOD.$method->name,
        ];
        if ($method->isStatic()) {
            $args['callMethod'] = $this->target->getShortName().'::'.$method->name;
        }

        $args += $this->getArgsAndDocs2Functions($method->getParameters());
        $dataProvider = !empty($args['params']) ? $this->bind('TestProvider', $args) : '';
        $args['docs'] = $this->setDataProvider2PhpDocs($args['docs'], $dataProvider);

        return $this->bind('TestFunction', $args).$dataProvider;
    }

    /**
     * @override
     *
     * @see Tdd\Command\AbstractCommand::isIgnoreParameter2Functions
     */
    protected function isIgnoreParameter2Functions(ReflectionParameter $parameter) : bool
    {
        return false;
    }

    /**
     * Set dataProvider to PHP Docs.
     *
     * @param string $docs         Traget PHP Docs
     * @param string $dataProvider Data Provider Values
     *
     * @return string PHPDocs after setting
     */
    private function setDataProvider2PhpDocs(string $docs, string $dataProvider) : string
    {
        preg_match('/(function )[a-zA-z0-9:punct:]*/', $dataProvider, $matches);
        if (count($matches) >= 2) {
            $providerName = str_replace($matches[1], '', $matches[0]);
            $format = self::DOCS_PREFIX.self::FORMAT_DATA_PROVIDER;
            $docs = sprintf($format, $providerName, self::DOCS_PREFIX.$docs);
        }

        return $docs;
    }
}
