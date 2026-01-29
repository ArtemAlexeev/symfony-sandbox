<?php

namespace App\Domain\Enum\User;

enum Language: string
{
    case English = 'en';
    case Russian = 'ru';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
