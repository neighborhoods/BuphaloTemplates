<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\AnnotationProcessors\ClassDefinition\V1\Builder;

use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition\Loader;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\PropertyInterface;
use Neighborhoods\Buphalo\V2\AnnotationProcessorInterface;

/** @noinspection PhpUnused, PhpSuperClassIncompatibleWithInterfaceInspection */
final class AdditionalFactories implements AnnotationProcessorInterface
{
    use Loader\AwareTrait;

    public function getReplacement(): string
    {
        $statements = [];

        foreach ($this->getClassDefinition()->getProperties() as $property) {
            $statement = $this->buildUseStatement($property);
            if ($statement !== null) {
                // keyed to ensure it is a set
                $statements[$statement] = $statement;
            }
        }

        return implode(PHP_EOL, $statements);
    }

    private function buildUseStatement(PropertyInterface $property): ?string
    {
        if (!$this->requiresFactory($property)) {
            return null;
        }

        $propertyClass = str_replace('Interface', '', $property->getDataType());
        $awareTraitClass = $propertyClass . '\\Builder\\Factory\\AwareTrait';

        return "    use $awareTraitClass;";
    }

    private function requiresFactory(PropertyInterface $property): bool
    {
        // preg_match returns 0, 1, or false
        $intResult = preg_match('/^\\\\Neighborhoods/', $property->getDataType());
        if ($intResult === false) {
            throw new \Exception('Error matching Data Type ' . $property->getDataType());
        }

        return (bool)$intResult;
    }
}
