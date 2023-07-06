<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\AnnotationProcessors\ClassDefinition\V1;

use Neighborhoods\Buphalo\V2\AnnotationProcessor\Context;
use Neighborhoods\Buphalo\V2\AnnotationProcessorInterface;

/** @noinspection PhpUnused */
class InterfaceConstantsAndAccessorsByArray implements AnnotationProcessorInterface
{
    use Context\AwareTrait {
        setAnnotationProcessorContext as public;
        getAnnotationProcessorContext as public;
    }

    public const KEY_CONSTANTS = 'constants';

    public const KEY_PROPERTIES = 'properties';
    public const PROPERTY_KEY_DATA_TYPE = 'data_type';

    /*
     * 1: NAME
     * 2: 'value'
     */
    private const FORMAT_CONSTANT = '    public const %1s = %2s;';

    /*
     * 1: Name
     * 2: type
     */
    private const FORMAT_GETTER = '    public function get%1$s(): %2$s;';

    /*
     * 1: Name
     * 2: type
     * 3: name
     */
    private const FORMAT_SETTER = '    public function set%1$s(%2$s $%3$s): self;';

    public function getReplacement(): string
    {
        $constants = [];
        $accessors = [];

        $context = $this->getAnnotationProcessorContext()->getStaticContextRecord();


        if (isset($context[self::KEY_CONSTANTS])) {
            foreach ($context[self::KEY_CONSTANTS] as $name => $value) {
                $constants[] = $this->buildUserDefinedConstant($name, $value);
            }
        }

        foreach ($context[self::KEY_PROPERTIES] as $name => $details) {
            $constants[] = $this->buildPropertyConstant($name);
            $accessors[] = $this->buildAccessors($name, $details);
        }

        return implode(PHP_EOL, $constants) . PHP_EOL . PHP_EOL . implode(PHP_EOL . PHP_EOL, $accessors);
    }

    private function buildUserDefinedConstant(string $name, $value): string
    {
        $valueString = \is_array($value) ? $this->convertArrayToStringValue($value) : var_export($value, true);

        return sprintf(self::FORMAT_CONSTANT, $name, $valueString);
    }

    private function buildPropertyConstant(string $name): string
    {
        return sprintf(self::FORMAT_CONSTANT, 'PROP_' . strtoupper($name), var_export($name, true));
    }

    private function convertArrayToStringValue(array $values): string
    {
        $arrayItems = [];

        foreach ($values as $key => $value) {
            if (\is_array($value)) {
                $valueToAppendToArray = $this->convertArrayToStringValue($value);
            } else {
                $valueToAppendToArray = var_export($value, true);
            }

            if (is_numeric($key)) {
                $arrayItems[] = $valueToAppendToArray;
            } else {
                $arrayItems[] = var_export($key, true) . ' => ' . $valueToAppendToArray;
            }
        }

        return '[ ' . implode(', ', $arrayItems) . ']';
    }

    private function buildAccessors(string $propertyName, array $propertyDetails): string
    {
        $accessors = [
            $this->buildGetter($propertyName, $propertyDetails),
            $this->buildSetter($propertyName, $propertyDetails),
        ];

        return implode(PHP_EOL, $accessors);
    }

    private function buildGetter(string $propertyName, array $propertyDetails): string
    {
        return sprintf(
            self::FORMAT_GETTER,
            $this->getPascalCaseName($propertyName),
            $propertyDetails[self::PROPERTY_KEY_DATA_TYPE]
        );
    }

    private function buildSetter(string $propertyName, array $propertyDetails): string
    {
        return sprintf(
            self::FORMAT_SETTER,
            $this->getPascalCaseName($propertyName),
            $propertyDetails[self::PROPERTY_KEY_DATA_TYPE],
            $propertyName,
        );
    }

    private function getPascalCaseName(string $name): string
    {
        $words = explode('_', $name);
        return implode(
            '',
            array_map(
                static function (string $word): string {
                    return ucfirst($word);
                },
                $words
            )
        );
    }
}
