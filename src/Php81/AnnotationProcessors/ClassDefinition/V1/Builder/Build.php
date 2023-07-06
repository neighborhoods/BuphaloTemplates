<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\AnnotationProcessors\ClassDefinition\V1\Builder;

use Neighborhoods\Buphalo\V2\AnnotationProcessorInterface;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition\Loader;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\PropertyInterface;

/** @noinspection PhpUnused, PhpSuperClassIncompatibleWithInterfaceInspection */

final class Build implements AnnotationProcessorInterface
{
    use Loader\AwareTrait;

    private const FORMAT_FETCH_FROM_RECORD = '$record[PrimaryActorNameInterface::PROP_%s]';
    private const FORMAT_SIMPLE_NULL_FALLBACK = '%s ?? null';
    private const FORMAT_BUILD_COMPLEX_OBJECT = '$this->get%sBuilderFactory()->create()->setRecord(%s)->build()';
    private const FORMAT_NULLABLE_COMPLEX = '
            isset(%s)
                ? %s
                : null
        ';

    private const FORMAT_ASSIGNMENT = '        $PrimaryActorName->set%s(%s);';

    public function getReplacement(): string
    {
        $statements = [];

        foreach ($this->getClassDefinition()->getProperties() as $property) {
            $statements[] = $this->buildSetStatement($property);
        }

        return implode(PHP_EOL, $statements);
    }

    private function buildSetStatement(PropertyInterface $property): string
    {
        $propertyName = $property->getName();

        $fetchFromRecord = sprintf(self::FORMAT_FETCH_FROM_RECORD, strtoupper($propertyName));

        if ($this->requiresBuilderFactory($property)) {
            $buildObject = sprintf(
                self::FORMAT_BUILD_COMPLEX_OBJECT,
                $this->getMethodName($property),
                $fetchFromRecord
            );

            $postFetch = $this->isPropertyNullable($property)
                ? sprintf(self::FORMAT_NULLABLE_COMPLEX, $fetchFromRecord, $buildObject)
                : $buildObject;
        } else {
            $postFetch = $this->isPropertyNullable($property)
                ? sprintf(self::FORMAT_SIMPLE_NULL_FALLBACK, $fetchFromRecord)
                : $fetchFromRecord;
        }

        $statement = sprintf(self::FORMAT_ASSIGNMENT, $this->getPascalCaseName($propertyName), $postFetch);

        return $statement;
    }

    private function isPropertyNullable(PropertyInterface $property): bool
    {
        return str_starts_with($property->getDataType(), '?');
    }

    private function getMethodName(PropertyInterface $property): string
    {
        $namespace = getenv('Neighborhoods_Buphalo_V2_TargetApplication_BuilderInterface__NamespacePrefix');
        $typeWithoutNull = preg_replace('/^\\?/', '', $property->getDataType());
        $typeWithoutVendor = preg_replace('/' . addslashes($namespace) . '/', '', $typeWithoutNull);
        $typeWithoutInterface = str_replace('Interface', '', $typeWithoutVendor);
        $typeWithoutSlashes = str_replace('\\', '', $typeWithoutInterface);

        return $typeWithoutSlashes;
    }

    private function requiresBuilderFactory(PropertyInterface $property): bool
    {
        // preg_match returns 0, 1, or false
        $intResult = preg_match('/^\??\\\\Neighborhoods/', $property->getDataType());
        if ($intResult === false) {
            throw new \LogicException('Error matching Data Type ' . $property->getDataType());
        }

        return (bool)$intResult;
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
