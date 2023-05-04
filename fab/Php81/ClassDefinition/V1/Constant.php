<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1;

final class Constant implements ConstantInterface
{

    private readonly string $name;

    private readonly mixed $value;

    public function getName(): string
    {
        return $this->name; // Will throw if not initialized
    }

    public function setName(string $name): self
    {
        $this->name = $name; // Will throw if already initialized
        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value; // Will throw if not initialized
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value; // Will throw if already initialized
        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
