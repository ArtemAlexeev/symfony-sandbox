<?php

namespace App\Application\Command;

use App\Application\DTO\ReactToUserDTO;
use App\Domain\Entity\User;

readonly class ReactToUserCommand
{
    public function __construct(
        public User $user,
        public ReactToUserDTO $dto
    ) {}
}
