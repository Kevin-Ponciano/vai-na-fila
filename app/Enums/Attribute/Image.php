<?php

namespace App\Enums\Attribute;

use ArchTech\Enums\Meta\MetaProperty;
use Attribute;

#[Attribute]
class Image extends MetaProperty
{
    protected function transform(mixed $value): string
    {
        return asset($value);
    }
}
