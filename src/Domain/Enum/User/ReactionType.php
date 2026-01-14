<?php

namespace App\Domain\Enum\User;

enum ReactionType: string
{
    case LIKE = 'like';
    case DISLIKE = 'dislike';
    case SUPERLIKE = 'superlike';

    public function getLabel(): string
    {
        return match($this) {
            self::LIKE => 'Like',
            self::DISLIKE => 'Dislike',
            self::SUPERLIKE => 'Super Like',
        };
    }
}
