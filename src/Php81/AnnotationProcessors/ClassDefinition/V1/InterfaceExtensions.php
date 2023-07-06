<?php

declare(strict_types=1);

namespace Neighborhoods\BuphaloTemplates\Php81\AnnotationProcessors\ClassDefinition\V1;

use Neighborhoods\Buphalo\V2\AnnotationProcessor\Context;
use Neighborhoods\Buphalo\V2\AnnotationProcessorInterface;

/** @noinspection PhpUnused */
class InterfaceExtensions implements AnnotationProcessorInterface
{
    use Context\AwareTrait {
        getAnnotationProcessorContext as public;
    }

    public function getReplacement(): string
    {
        $context = $this->getAnnotationProcessorContext()->getStaticContextRecord();
        return empty($context) ? '' : 'extends ' . implode(', ', $context);
    }

}
