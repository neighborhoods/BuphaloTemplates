<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\AnnotationProcessors\ClassDefinition\V1;

use Neighborhoods\Buphalo\V2\AnnotationProcessor\Context;
use Neighborhoods\Buphalo\V2\AnnotationProcessorInterface;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition\Loader;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ConstantInterface;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\PropertyInterface;

/** @noinspection PhpUnused */
class InterfaceConstantsAndAccessorsByFile implements AnnotationProcessorInterface
{
    use Loader\AwareTrait;

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

        foreach ($this->getClassDefinition()->getConstants() as $constant) {
            $constants[] = $this->buildUserDefinedConstant($constant);
        }

        foreach ($this->getClassDefinition()->getProperties() as $property) {
            $constants[] = $this->buildPropertyConstant($property);
            $accessors[] = $this->buildAccessors($property);
        }

        return implode(PHP_EOL, $constants) . PHP_EOL . PHP_EOL . implode(PHP_EOL . PHP_EOL, $accessors);
    }

    private function buildUserDefinedConstant(ConstantInterface $constant): string
    {
        $value = $constant->getValue();
        $valueString = \is_array($value) ? $this->convertArrayToStringValue($value) : var_export($value, true);

        return sprintf(self::FORMAT_CONSTANT, $constant->getName(), $valueString);
    }

    private function buildPropertyConstant(PropertyInterface $property): string
    {
        $name = $property->getName();
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

    private function buildAccessors(PropertyInterface $property): string
    {
        $accessors = [
            $this->buildGetter($property),
            $this->buildSetter($property),
        ];

        return implode(PHP_EOL, $accessors);
    }

    private function buildGetter(PropertyInterface $property): string
    {
        return sprintf(
            self::FORMAT_GETTER,
            $this->getPascalCaseName($property->getName()),
            $property->getDataType()
        );
    }

    private function buildSetter(PropertyInterface $property): string
    {
        return sprintf(
            self::FORMAT_SETTER,
            $this->getPascalCaseName($property->getName()),
            $property->getDataType(),
            $property->getName(),
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
