<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Property;

use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\PropertyInterface;

final class Set implements SetInterface
{
    private array $data = [];

    // Initialization Methods

    public function __construct(PropertyInterface ...$values)
    {
        $this->add(...$values);
    }

    public function hydrate(PropertyInterface ...$values): SetInterface
    {
        if (!$this->isEmpty()) {
            throw new \LogicException('String Set is not empty');
        }

        $this->__construct(...$values);

        return $this;
    }


    // Mutators

    public function add(PropertyInterface ...$values): SetInterface
    {
        foreach ($values as $value) {
            $this->data[$this->getIdentifier($value)] = $value;
        }

        return $this;
    }

    public function remove(PropertyInterface ...$values): SetInterface
    {
        foreach ($values as $value) {
            unset($this->data[$this->getIdentifier($value)]);
        }

        return $this;
    }

    public function clear(): SetInterface
    {
        $this->data = [];

        return $this;
    }


    // Inspectors

    public function contains(PropertyInterface $value): bool
    {
        return isset($this->data[$this->getIdentifier($value)]);
    }

    public function containsAll(PropertyInterface ...$values): bool
    {
        foreach ($values as $value) {
            if (!$this->contains($value)) {
                return false;
            }
        }

        return true;
    }

    public function containsAny(PropertyInterface ...$values): bool
    {
        if (!\count($values)) {
            throw new \InvalidArgumentException('ContainsAny requires at least one value to search for.');
        }

        foreach ($values as $value) {
            if ($this->contains($value)) {
                return true;
            }
        }

        return false;
    }

    public function count(): int
    {
        return \count($this->data);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }


    // Operations that return a new Set

    public function copy(): SetInterface
    {
        return new self(...$this);
    }

    public function diff(SetInterface $other): SetInterface
    {
        return $this->filter(
            static function ($element) use ($other) {
                return !$other->contains($element);
            }
        );
    }

    public function filter(callable $callback): SetInterface
    {
        $filtered = new self();

        foreach($this as $value) {
            $shouldInclude = $callback($value);
            if ($shouldInclude === true) {
                $filtered->add($value);
            } elseif ($shouldInclude !== false) {
                throw new \TypeError('Filter callback MUST return boolean');
            }
        }

        return $filtered;
    }

    public function intersect(SetInterface $other): SetInterface
    {
        return $this->filter(
            static function ($element) use ($other) {
                return $other->contains($element);
            }
        );
    }

    public function map(callable $callback): SetInterface
    {
        $mapped = new self();
        foreach ($this as $value) {
            $mapped->add($callback($value));
        }

        return $mapped;
    }

    public function union(SetInterface $other): SetInterface
    {
        $union = $this->copy();
        $union->add(...$other);

        return $union;
    }

    public function xor(SetInterface $other): SetInterface
    {
        return $this->union($other)->filter(
            function ($element) use ($other) {
                return $this->contains($element) xor $other->contains($element);
            }
        );
    }

    // Other Operations

    public function reduce(callable $callback, $initial)
    {
        $carry = $initial;

        foreach ($this as $value) {
            $carry = $callback($carry, $value);
        }

        return $carry;
    }

    public function toArray(): array
    {
        return array_values($this->data);
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->data as $value) {
            yield $value;
        }
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    private function getIdentifier(PropertyInterface $value): string
    {
        return $value->getName();
    }
}
