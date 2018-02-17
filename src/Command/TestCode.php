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
    const DOCS_ARGUMENT_FORMAT = self::DOCS_PREFIX.'@param %s $%s any param';
    /**
     * Data Provider of Test Function Docs Format.
     */
    const DATA_PROVIDER_FORMAT = self::DOCS_PREFIX.'@dataProvider %s%s';
    /**
     * Format Call Method.
     */
    const FORMAT_CALL_METHOD = '$this->target->';
    /**
     * Type Unknown.
     */
    const TYPE_UNKNOWN = 'mixed';

    /**
     * File Ext.
     */
    const FILE_EXT_TARGET = self::DEFAULT_FILE_EXT;
    const FILE_EXT_OUTPUT = 'Test.php';

    /**
     * @override
     *
     * @see Tdd\Command\AbstractCommand::create
     */
    public function create() : bool
    {
        $fileName = $this->getOutputFileName($this->target, $this->options);
        $this->output($fileName, $this->bind('TestCase', $this->getOutputValues()));

        return true;
    }

    /**
     * Get output values.
     *
     * @return array Output Values
     */
    protected function getOutputValues() : array
    {
        $values = [
            'className'     => $this->target->getName(),
            'shortName'     => $this->target->getShortName(),
            'testFunctions' => '',
        ];
        foreach ($this->target->getMethods() as $method) {
            if ($values['className'] === $method->class && $method->isPublic()) {
                $values['testFunctions'] .= $this->getFunctions($method);
            }
        }

        return $values;
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
