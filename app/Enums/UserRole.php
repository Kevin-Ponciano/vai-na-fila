<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;


enum UserRole: string
{
    use Names, Values, Options;

    case ADMIN = 'admin';
    case OPERATOR = 'operator';
}
