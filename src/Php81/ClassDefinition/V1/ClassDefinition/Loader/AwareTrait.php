<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition\Loader;

use Neighborhoods\Buphalo\V2\AnnotationProcessor\Context;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition\Loader;
use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinitionInterface;

trait AwareTrait
{
    use Context\AwareTrait {
        setAnnotationProcessorContext as public;
        getAnnotationProcessorContext as public;
    }

    private readonly ClassDefinitionInterface $class_definition;

    private function setClassDefinition(ClassDefinitionInterface $class_definition): self
    {
        $this->class_definition = $class_definition; // Will throw if already set
        return $this;
    }

    private function loadClassDefinition(): self
    {
        $loader = new Loader($this->getAnnotationProcessorContext());
        $this->setClassDefinition($loader->getClassDefinition());
        return $this;
    }

    private function getClassDefinition(): ClassDefinitionInterface
    {
        try {
            return $this->class_definition; // Will throw if uninitialized
        } catch (\Error) {
            $this->loadClassDefinition();
        }

        return $this->class_definition;
    }
}
