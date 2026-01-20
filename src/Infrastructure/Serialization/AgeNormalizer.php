<?php

namespace App\Infrastructure\Serialization;

use App\Domain\ValueObject\Age;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AgeNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return $data->getValue();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Age;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => true,
        ];
    }
}
