<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Property;

use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\PropertyInterface;

interface SetInterface extends CollectionInterface
{
    // Initialization
    public function hydrate(PropertyInterface ...$values): SetInterface;

    // Mutators
    public function add(PropertyInterface ...$values): SetInterface;

    public function remove(PropertyInterface ...$values): SetInterface;

    public function clear(): SetInterface;

    // Inspectors
    public function contains(PropertyInterface $value): bool;

    public function containsAll(PropertyInterface ...$values): bool;

    public function containsAny(PropertyInterface ...$values): bool;

    /* @see \Countable::count() */
    public function count(): int;

    public function isEmpty(): bool;

    public function copy(): SetInterface;

    public function diff(SetInterface $other): SetInterface;

    public function filter(callable $callback): SetInterface;

    public function intersect(SetInterface $other): SetInterface;

    public function map(callable $callback): SetInterface;

    public function union(SetInterface $other): SetInterface;

    public function xor(SetInterface $other): SetInterface;

    // Operations that return something else
    public function reduce(callable $callback, $initial);
}
