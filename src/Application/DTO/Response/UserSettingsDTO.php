<?php

namespace App\Application\DTO\Response;

use App\Domain\Entity\UserSettings;
use App\Domain\Enum\User\Language;

readonly class UserSettingsDTO
{
    public function __construct(
        public bool $pushEnabled,
        public bool $emailEnabled,
        public string $language
    ) {
    }

    public static function fromEntity(?UserSettings $settings): self
    {
        return new self(
            $settings?->getPushEnabled() ?? false,
            $settings?->getEmailEnabled() ?? false,
            $settings?->getLanguage()->value ?? Language::English->value,
        );
    }
}
