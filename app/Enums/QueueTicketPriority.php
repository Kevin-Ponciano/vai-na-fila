<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum QueueTicketPriority: string
{
    use Names, Values, Options;

    case NORMAL = 'normal';
    case PRIORITY = 'priority';
}
