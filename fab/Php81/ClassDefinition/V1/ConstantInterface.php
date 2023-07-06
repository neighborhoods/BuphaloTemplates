<?php
declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1;

interface ConstantInterface 
    extends \JsonSerializable
{
    public const PROP_NAME = 'name';
    public const PROP_VALUE = 'value';

    public function getName(): string;
    public function setName(string $name): self;

    public function getValue(): mixed;
    public function setValue(mixed $value): self;
}
