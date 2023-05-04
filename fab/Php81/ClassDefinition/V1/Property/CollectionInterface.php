<?php
declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Property;

interface CollectionInterface extends \JsonSerializable, \IteratorAggregate
{
    public function getIterator(): \Traversable;

    public function toArray(): array;
}
