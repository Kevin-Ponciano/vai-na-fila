<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum NotificationMethod: string
{
    use Names, Values;

    case SMS = 'sms';
    case WHATSAPP = 'whatsapp';
}
