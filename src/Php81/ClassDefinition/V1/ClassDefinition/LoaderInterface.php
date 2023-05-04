<?php
declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinition;

use Neighborhoods\BuphaloTemplates\Php81\ClassDefinition\V1\ClassDefinitionInterface;

interface LoaderInterface
{
    public function getClassDefinition(): ClassDefinitionInterface;
}
