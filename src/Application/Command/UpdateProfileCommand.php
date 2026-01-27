<?php

namespace App\Application\Command;

use App\Application\DTO\ProfileDTO;
use App\Domain\Entity\Profile;

readonly class UpdateProfileCommand implements AsyncCommandInterface
{
    public function __construct(
        public Profile $profile,
        public ProfileDTO $dto
    ) {}
}
