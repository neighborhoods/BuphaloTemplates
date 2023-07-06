<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Constant;

use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ConstantInterface;

interface SetInterface extends CollectionInterface
{
    // Initialization
    public function hydrate(ConstantInterface ...$values): SetInterface;

    // Mutators
    public function add(ConstantInterface ...$values): SetInterface;

    public function remove(ConstantInterface ...$values): SetInterface;

    public function clear(): SetInterface;

    // Inspectors
    public function contains(ConstantInterface $value): bool;

    public function containsAll(ConstantInterface ...$values): bool;

    public function containsAny(ConstantInterface ...$values): bool;

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
