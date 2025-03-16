<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum UserRole: string
{
    use Names;
    use Values;

    case ADMIN = 'admin';
    case OPERATOR = 'operator';
}
