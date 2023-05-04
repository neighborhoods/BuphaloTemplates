<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition;

use Neighborhoods\Buphalo\V2\AnnotationProcessor;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinitionInterface;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Constant;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Property;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\PropertyInterface;
use Symfony\Component\Yaml\Yaml;

final class Loader implements LoaderInterface
{
    use AnnotationProcessor\Context\AwareTrait;

    private readonly ClassDefinitionInterface $class_definition;

    public function __construct(AnnotationProcessor\ContextInterface $context)
    {
        $this->setAnnotationProcessorContext($context);
        $this->loadClassDefinition();
    }

    private function loadClassDefinition(): LoaderInterface
    {
        $fabricationFile = $this->getAnnotationProcessorContext()->getFabricationFile();
        $directory = $fabricationFile->getDirectoryPath();
        $fileName = $fabricationFile->getFileName();
        $parsedDefinitionFileContents = Yaml::parseFile($directory . '/' . $fileName . '.class.definition.yml');

        $classDefinition = $this->buildClassDefinition($parsedDefinitionFileContents);
        $this->setClassDefinition($classDefinition);

        return $this;
    }

    private function buildClassDefinition(array $definitionArray): ClassDefinitionInterface
    {
        $constants = new Constant\Set();
        foreach ($definitionArray[ClassDefinitionInterface::PROP_CONSTANTS] ?? [] as $name => $value) {
            $constant = new Constant();
            $constant->setName($name)
                ->setValue($value);
            $constants->add($constant);
        }

        $properties = new Property\Set();
        foreach ($definitionArray[ClassDefinitionInterface::PROP_PROPERTIES] as $name => $details) {
            $property = new Property();
            $property->setName($name)
                ->setDataType($details[PropertyInterface::PROP_DATA_TYPE]);
            $properties->add($property);
        }

        $classDefinition = new ClassDefinition();
        $classDefinition
            ->setConstants($constants)
            ->setProperties($properties)
            ->setIdentityField($definitionArray[ClassDefinitionInterface::PROP_IDENTITY_FIELD] ?? null);

        return $classDefinition;
    }

    public function getClassDefinition(): ClassDefinitionInterface
    {
        try {
            return $this->class_definition;
        } catch (\Error) {
            $this->loadClassDefinition();
        }

        return $this->class_definition;
    }

    private function setClassDefinition(ClassDefinitionInterface $class_definition): self
    {
        $this->class_definition = $class_definition; // Will throw if already initialized
        return $this;
    }

}
