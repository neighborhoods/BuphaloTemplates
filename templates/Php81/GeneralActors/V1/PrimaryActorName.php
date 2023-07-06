<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplateTree\RelativeNamespace;

final class PrimaryActorName implements PrimaryActorNameInterface
{
/** @neighborhoods-buphalo:annotation-processor ClassPropertiesAndAccessors
// TODO: Implement properties, and accessors.
 */

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
