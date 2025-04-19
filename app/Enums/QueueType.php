<?php

namespace App\Enums;

use App\Enums\Attribute\Description;
use App\Enums\Attribute\Image;
use ArchTech\Enums\Meta\Meta;
use ArchTech\Enums\Metadata;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

#[Meta(Description::class)]
enum QueueType: string
{
    use Names, Values, Options, Metadata;

    #[Description('Fila no setor de Padaria'), Image('assets/img/padaria_cropped.png')]
    case BAKERY = 'bakery';

    #[Description('Fila no setor de Açougue'), Image('assets/img/acougue_cropped.png')]
    case MEAT = 'meat';

    #[Description('Fila no setor de Frios'), Image('assets/img/frios_cropped.png')]
    case DELI = 'deli';


}
