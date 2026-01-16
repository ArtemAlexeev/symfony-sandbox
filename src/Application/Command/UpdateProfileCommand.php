<?php

namespace App\Application\Command;

use App\Application\DTO\ProfileDTO;
use App\Domain\Entity\Profile;

readonly class UpdateProfileCommand
{
    public function __construct(
        public Profile $profile,
        public ProfileDTO $dto
    ) {}
}
