<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum QueueTicketStatus: string
{
    use Names, Values;

    case WAITING = 'waiting';
    case CALLED = 'called';
    CASE EXPIRED = 'expired';
    case CANCELLED = 'cancelled';
}
