<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum NotificationStatus: string
{
    use Names, Values;

    case SENT = 'sent';
    case FAILED = 'failed';
}
