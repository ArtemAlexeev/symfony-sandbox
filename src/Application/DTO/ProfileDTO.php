<?php

namespace App\Application\DTO;

use App\Domain\Enum\User\Gender;
use App\Domain\Enum\User\Status;
use Symfony\Component\Validator\Constraints;

readonly class ProfileDTO
{
    public function __construct(
        #[Constraints\Type('string')]
        #[Constraints\Length(min: 2, max: 50)]
        public ?string $firstName,
        #[Constraints\Type('string')]
        #[Constraints\Length(min: 2, max: 50)]
        public ?string $lastName,
        #[Constraints\Type('integer')]
        public ?int $age,
        #[Constraints\Choice(callback: [Gender::class, 'values'], message: "That is not a valid gender")]
        public ?string $gender,
        #[Constraints\Choice(callback: [Status::class, 'values'], message: "That is not a valid status")]
        public ?string $status,
        #[Constraints\Type('string')]
        #[Constraints\Length(max: 255)]
        public ?string $description,
        #[Constraints\Type('string')]
        public ?string $avatar,
    ) {
    }
}
