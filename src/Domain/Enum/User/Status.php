<?php

namespace App\Domain\Enum\User;

enum Status: string
{
    case READY_TO_CHAT = 'ready_to_chat';
    case READY_TO_MEET = 'ready_to_meet';
    case LONG_RELATIONS_ONLY = 'long_relations_only';
    case FRIENDSHIP_ONLY = 'friendship_only';
    case OPEN_TO_SHORT_DATE = 'open_to_short_date';

    public function label(): string
    {
        return match($this) {
            self::READY_TO_CHAT => 'Ready to chat',
            self::READY_TO_MEET => 'Ready to meet',
            self::LONG_RELATIONS_ONLY => 'Long relations only',
            self::FRIENDSHIP_ONLY => 'Friendship only',
            self::OPEN_TO_SHORT_DATE => 'Open to short date',
        };
    }

    /**
     * Get all enum values as an array
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
