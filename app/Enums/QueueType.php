<?php

namespace App\Enums;

use App\Enums\Attribute\Description;
use App\Enums\Attribute\Image;
use App\Enums\Attribute\Name;
use ArchTech\Enums\Meta\Meta;
use ArchTech\Enums\Metadata;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

#[Meta(Description::class, Image::class, Name::class)]
enum QueueType: string
{
    use Names, Values, Options, Metadata;

    #[Name('Padaria'), Description('Fila no setor de Padaria'), Image('assets/img/padaria_cropped.png')]
    case BAKERY = 'bakery';

    #[Name('Açougue'), Description('Fila no setor de Açougue'), Image('assets/img/acougue_cropped.png')]
    case MEAT = 'meat';

    #[Name('Frios'), Description('Fila no setor de Frios'), Image('assets/img/frios_cropped.png')]
    case DELI = 'deli';
}
