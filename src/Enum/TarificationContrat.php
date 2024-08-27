<?php

namespace App\Enum;

enum TarificationContrat: string
{
    case MENSUELLE = 'Mensuelle';
    case ANNUELLE = 'Annuelle';

    public static function toString(self $value): string
    {
        return $value->value;
    }
}