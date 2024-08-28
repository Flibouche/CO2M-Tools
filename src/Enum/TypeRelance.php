<?php

namespace App\Enum;

enum TypeRelance: string
{
    case MAIL = 'Mail';
    case TELEPHONE = 'Téléphone';
    case COURRIER = 'Courrier';
    case AUTRE = 'Autre';

    public static function toString(self $value): string
    {
        return $value->value;
    }
}