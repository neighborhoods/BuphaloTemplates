<?php
declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplateTree\RelativeNamespace\PrimaryActorName;

interface CollectionInterface extends \JsonSerializable, \IteratorAggregate
{
    public function getIterator(): \Traversable;

    public function toArray(): array;
}
