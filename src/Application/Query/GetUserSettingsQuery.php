<?php

namespace App\Application\Query;

use App\Domain\Entity\User;

final readonly class GetUserSettingsQuery
{
    public function __construct(public User $user)
    {
    }
}
