<?php
declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1;

interface PropertyInterface 
    extends \JsonSerializable
{
    public const PROP_NAME = 'name';
    public const PROP_DATA_TYPE = 'data_type';

    public function getName(): string;
    public function setName(string $name): self;

    public function getDataType(): string;
    public function setDataType(string $data_type): self;
}
