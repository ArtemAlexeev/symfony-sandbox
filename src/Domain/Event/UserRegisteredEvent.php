<?php

namespace App\Domain\Event;

use App\Domain\Entity\User;

readonly class UserRegisteredEvent
{
    public function __construct(
        public User $user
    ) {}
}
