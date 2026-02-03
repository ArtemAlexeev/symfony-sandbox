<?php

namespace App\Application\Command;

use App\Application\DTO\UpdateUserSettingsDTO;
use App\Domain\Entity\User;

readonly class UpdateUserSettingsCommand
{
    public function __construct(
        public User $user,
        public UpdateUserSettingsDTO $dto
    ) {}
}
