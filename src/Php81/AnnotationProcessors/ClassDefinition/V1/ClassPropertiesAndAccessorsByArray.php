<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\AnnotationProcessors\ClassDefinition\V1;

use Neighborhoods\Buphalo\V2\AnnotationProcessorInterface;
use Neighborhoods\Buphalo\V2\AnnotationProcessor\Context;

/** @noinspection PhpUnused */
class ClassPropertiesAndAccessorsByArray implements AnnotationProcessorInterface
{
    use Context\AwareTrait {
        setAnnotationProcessorContext as public;
        getAnnotationProcessorContext as public;
    }

    public const KEY_PROPERTIES = 'properties';

    public const PROPERTY_KEY_DATA_TYPE = 'data_type';

    /*
     * 1: type
     * 2: name
     */
    private const FORMAT_PROPERTY = '
    private readonly %1$s $%2$s;';

    /*
     * 1: Name
     * 2: type
     * 3: name
     */
    private const FORMAT_GETTER = '
    public function get%1$s(): %2$s
    {
        return $this->%3$s; // Will throw if not initialized
    }';

    /*
     * 1: Name
     * 2: type
     * 3: name
     */
    private const FORMAT_SETTER = '
    public function set%1$s(%2$s $%3$s): self
    {
        $this->%3$s = $%3$s; // Will throw if already initialized
        return $this;
    }';

    public function getReplacement(): string
    {
        $properties = [];
        $accessors = [];

        $context = $this->getAnnotationProcessorContext()->getStaticContextRecord();
        foreach ($context[self::KEY_PROPERTIES] as $name => $propertyDetails) {
            $properties[] = $this->buildProperty($name, $propertyDetails);
            $accessors[] = $this->buildAccessors($name, $propertyDetails);
        }

        return implode(PHP_EOL, $properties) . PHP_EOL . implode(PHP_EOL, $accessors);
    }

    private function buildProperty(string $name, array $propertyDetails): string
    {
        return sprintf(self::FORMAT_PROPERTY, $propertyDetails[self::PROPERTY_KEY_DATA_TYPE], $name);
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
            $propertyDetails[self::PROPERTY_KEY_DATA_TYPE],
            $propertyName,
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

    private function getPascalCaseName(string $propertyName): string
    {
        $words = explode('_', $propertyName);
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
