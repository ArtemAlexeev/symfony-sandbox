<?php

namespace App\Domain\Enum\User;

enum ReactionType: string
{
    case LIKE = 'like';
    case DISLIKE = 'dislike';
    case SUPERLIKE = 'super';

    public function getLabel(): string
    {
        return match($this) {
            self::LIKE => 'Like',
            self::DISLIKE => 'Dislike',
            self::SUPERLIKE => 'Super Like',
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
