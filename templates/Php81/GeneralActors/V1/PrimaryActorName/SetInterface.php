<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplateTree\RelativeNamespace\PrimaryActorName;

use Neighborhoods\BuphaloTemplateTree\NamespacedPrimaryActorName;

interface SetInterface extends CollectionInterface
{
    // Initialization
    public function hydrate(ParentActorNameInterface ...$values): SetInterface;

    // Mutators
    public function add(ParentActorNameInterface ...$values): SetInterface;

    public function remove(ParentActorNameInterface ...$values): SetInterface;

    public function clear(): SetInterface;

    // Inspectors
    public function contains(ParentActorNameInterface $value): bool;

    public function containsAll(ParentActorNameInterface ...$values): bool;

    public function containsAny(ParentActorNameInterface ...$values): bool;

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
