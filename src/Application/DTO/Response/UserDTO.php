<?php

namespace App\Application\DTO\Response;

use App\Domain\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $email,
        #[Context([DateTimeNormalizer::FORMAT_KEY => 'd-m-Y H:i'])]
        public DateTimeImmutable $createdAt,
    ) {
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getEmail(),
            $user->getCreatedAt(),
        );
    }
}
