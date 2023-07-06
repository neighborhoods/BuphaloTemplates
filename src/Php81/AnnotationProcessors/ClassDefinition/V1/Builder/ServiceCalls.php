<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\AnnotationProcessors\ClassDefinition\V1\Builder;

use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition\Loader;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\PropertyInterface;
use Neighborhoods\Buphalo\V2\AnnotationProcessorInterface;

/** @noinspection PhpUnused, PhpSuperClassIncompatibleWithInterfaceInspection */
final class ServiceCalls implements AnnotationProcessorInterface
{
    private const FORMAT_CALL = "      - [set%sBuilderFactory, ['@%s']]";

    use Loader\AwareTrait;

    public function getReplacement(): string
    {
        $statements = [];

        foreach ($this->getClassDefinition()->getProperties() as $property) {
            $statement = $this->buildServiceCallStatement($property);
            if ($statement !== null) {
                // keyed to ensure it is a set
                $statements[$statement] = $statement;
            }
        }

        return implode(PHP_EOL, $statements);
    }

    private function buildServiceCallStatement(PropertyInterface $property): ?string
    {
        if (!$this->requiresFactory($property)) {
            return null;
        }

        $propertyClass = str_replace('Interface', '', $property->getDataType());
        $propertyClass = preg_replace('/^\\\\/', '', $propertyClass);
        $builderFactoryServiceReference = $propertyClass . '\\Builder\\FactoryInterface';

        return sprintf(self::FORMAT_CALL, $this->getMethodName($property), $builderFactoryServiceReference);
    }

    private function requiresFactory(PropertyInterface $property): bool
    {
        // preg_match returns 0, 1, or false
        $intResult = preg_match('/^\\\\Neighborhoods/', $property->getDataType());
        if ($intResult === false) {
            throw new \LogicException('Error matching Data Type ' . $property->getDataType());
        }

        return (bool)$intResult;
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
}
