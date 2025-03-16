<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum QueueTicketType: string
{
    use Names, Values;

    CASE QRCODE = 'qrcode';
    CASE PRINT = 'print';
}
