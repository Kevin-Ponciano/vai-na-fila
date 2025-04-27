<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum QueueTicketStatus: string
{
    use Names, Values;

    case PROCESSING = 'processing';   // (#1) Ticket recém-gerado: criando QR / imprimindo
    case WAITING = 'waiting';      // (#2) Na fila, aguardando ser chamado
    case CALLING = 'calling';      // (#3) Display/painel está chamando o número
    case IN_SERVICE = 'in_service';   // (#4) Cliente atendeu, atendimento em andamento
    case CALLED = 'called';       // (#5) Atendimento concluído com sucesso

    /* Estados de saída sem atendimento */
    case EXPIRED = 'expired';      // Timeout (cliente não apareceu a tempo)
    case CANCELLED = 'cancelled';    // Operador/cliente cancelou antes de ser atendido


}
