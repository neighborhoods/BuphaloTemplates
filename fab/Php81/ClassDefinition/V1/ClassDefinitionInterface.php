<?php
declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1;

interface ClassDefinitionInterface 
    extends \JsonSerializable
{
    public const PROP_CONSTANTS = 'constants';
    public const PROP_PROPERTIES = 'properties';
    public const PROP_IDENTITY_FIELD = 'identity_field';

    public function getConstants(): \Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Constant\SetInterface;
    public function setConstants(\Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Constant\SetInterface $constants): self;

    public function getProperties(): \Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Property\SetInterface;
    public function setProperties(\Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\Property\SetInterface $properties): self;

    public function getIdentityField(): ?string;
    public function setIdentityField(?string $identity_field): self;
}
