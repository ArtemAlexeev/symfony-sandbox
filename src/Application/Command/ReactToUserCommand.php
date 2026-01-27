<?php

namespace App\Application\Command;

use App\Application\DTO\ReactToUserDTO;
use App\Domain\Entity\User;

readonly class ReactToUserCommand implements AsyncCommandInterface
{
    public function __construct(
        public int $userId,
    ) {}
}
