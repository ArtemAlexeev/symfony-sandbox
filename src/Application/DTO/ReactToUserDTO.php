<?php

namespace App\Application\DTO;

use App\Domain\Enum\User\ReactionType;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ReactToUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        public int $targetUserId,

        #[Assert\NotBlank]
        #[Assert\Choice(callback: [ReactionType::class, 'values'])]
        public string $type,
    ) {}
}
