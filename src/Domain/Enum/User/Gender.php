<?php

namespace App\Domain\Enum\User;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
