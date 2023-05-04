<?php
declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplateTree\RelativeNamespace\RelativeParentActorClassPath;

use Neighborhoods\BuphaloTemplateTree\RelativeNamespace\RelativeParentActorClassPathInterface;

trait AwareTrait
{
    private readonly ParentActorNameInterface $NamespacedParentActorName;

    public function setNamespacedParentActorName(ParentActorNameInterface $ParentActorName): self
    {
        $this->NamespacedParentActorName = $ParentActorName; // will throw if already set

        return $this;
    }

    private function getNamespacedParentActorName(): ParentActorNameInterface
    {
        return $this->NamespacedParentActorName; // will throw if unset
    }

    private function hasNamespacedParentActorName(): bool
    {
        try {
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->NamespacedParentActorName;
            return true;
        } catch (\Error $e) {
            return false;
        }
    }
}
