<?php

namespace App\Enums;

use App\Enums\Attribute\Description;
use App\Enums\Attribute\Name;
use ArchTech\Enums\Metadata;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;
use ArchTech\Enums\Meta\Meta;

#[Meta(Name::class, Description::class)]
enum QueueTicketStatus: string
{
    use Names, Values, Metadata;

    #[Name('Processando')]
    case PROCESSING = 'processing';
    #[Name('Aguardando')]
    case WAITING = 'waiting';
    #[Name('Chamando')]
    case CALLING = 'calling';
    #[Name('Atendendo')]
    case IN_SERVICE = 'in_service';
    #[Name('Chamado')]
    case CALLED = 'called';
    #[Name('Expirado')]
    case EXPIRED = 'expired';
    #[Name('Cancelado')]
    case CANCELLED = 'cancelled';
}
