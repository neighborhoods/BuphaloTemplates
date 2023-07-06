<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1;

final class ClassDefinition implements ClassDefinitionInterface
{

    private readonly \Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Constant\SetInterface $constants;

    private readonly \Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Property\SetInterface $properties;

    private readonly ?string $identity_field;

    public function getConstants(): \Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Constant\SetInterface
    {
        return $this->constants; // Will throw if not initialized
    }

    public function setConstants(\Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Constant\SetInterface $constants): self
    {
        $this->constants = $constants; // Will throw if already initialized
        return $this;
    }

    public function getProperties(): \Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Property\SetInterface
    {
        return $this->properties; // Will throw if not initialized
    }

    public function setProperties(\Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Property\SetInterface $properties): self
    {
        $this->properties = $properties; // Will throw if already initialized
        return $this;
    }

    public function getIdentityField(): ?string
    {
        return $this->identity_field; // Will throw if not initialized
    }

    public function setIdentityField(?string $identity_field): self
    {
        $this->identity_field = $identity_field; // Will throw if already initialized
        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
