<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\AnnotationProcessors\ClassDefinition\V1;

use Neighborhoods\Buphalo\V2\AnnotationProcessorInterface;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition\Loader;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\PropertyInterface;

/** @noinspection PhpUnused */
class ClassPropertiesAndAccessorsByFile implements AnnotationProcessorInterface
{
    use Loader\AwareTrait;

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

        foreach ($this->getClassDefinition()->getProperties() as $property) {
            $properties[] = $this->buildProperty($property);
            $accessors[] = $this->buildAccessors($property);
        }

        return implode(PHP_EOL, $properties) . PHP_EOL . implode(PHP_EOL, $accessors);
    }

    private function buildProperty(PropertyInterface $property): string
    {
        return sprintf(self::FORMAT_PROPERTY, $property->getDataType(), $property->getName());
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
            $property->getDataType(),
            $property->getName(),
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
