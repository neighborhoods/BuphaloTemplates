<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1;

final class Property implements PropertyInterface
{

    private readonly string $name;

    private readonly string $data_type;

    public function getName(): string
    {
        return $this->name; // Will throw if not initialized
    }

    public function setName(string $name): self
    {
        $this->name = $name; // Will throw if already initialized
        return $this;
    }

    public function getDataType(): string
    {
        return $this->data_type; // Will throw if not initialized
    }

    public function setDataType(string $data_type): self
    {
        $this->data_type = $data_type; // Will throw if already initialized
        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
