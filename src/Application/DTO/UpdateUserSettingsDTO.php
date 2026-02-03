<?php

namespace App\Application\DTO;

use App\Domain\Enum\User\Language;
use Symfony\Component\Validator\Constraints;

readonly class UpdateUserSettingsDTO
{
    public function __construct(
        #[Constraints\Type('bool')]
        public ?bool $pushEnabled = null,

        #[Constraints\Type('bool')]
        public ?bool $emailEnabled = null,

        #[Constraints\Choice(callback: [Language::class, 'values'], message: "That is not a valid language")]
        public ?string $language = null,
    ) {}
}
