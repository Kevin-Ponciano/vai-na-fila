<?php

namespace App\Enums;

use App\Enums\Attribute\Name;
use ArchTech\Enums\Meta\Meta;
use ArchTech\Enums\Metadata;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

#[Meta(Name::class)]
enum QueueTicketPriority: string
{
    use Names, Values, Options, Metadata;

    #[Name('Geral')]
    case NORMAL = 'geral';
    #[Name('Preferencial')]
    case PRIORITY = 'priority';
}
