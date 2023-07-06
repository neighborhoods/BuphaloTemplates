<?php
declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplateTree\RelativeNamespace\PrimaryActorName;

use Neighborhoods\BuphaloTemplateTree\RelativeNamespace\PrimaryActorNameInterface;

class Builder implements BuilderInterface
{
    use Factory\AwareTrait;
/** @neighborhoods-buphalo:annotation-processor Builder.additional_factories
*/

    private array $record;

    public function build(): PrimaryActorNameInterface
    {
        $PrimaryActorName = $this->getNamespacedPrimaryActorNameFactory()->create();
        $record = $this->getRecord();

/** @neighborhoods-buphalo:annotation-processor Builder.build
*/

        return $PrimaryActorName;
    }

    private function getRecord(): array
    {
        return $this->record; // will throw if not initialized
    }

    public function setRecord(array $record): self
    {
        $this->record = $record; // will throw if already initialized
        return $this;
    }
}
