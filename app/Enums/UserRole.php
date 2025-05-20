<?php

namespace App\Enums;

use App\Enums\Attribute\Name;
use ArchTech\Enums\Metadata;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;
use ArchTech\Enums\Meta\Meta;

#[Meta(Name::class)]
enum UserRole: string
{
    use Names, Values, Options, Metadata;

    #[Name('Administrador')]
    case ADMIN = 'admin';

    #[Name('Operador')]
    case OPERATOR = 'operator';
}
