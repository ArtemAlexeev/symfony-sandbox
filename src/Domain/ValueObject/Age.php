<?php

namespace App\Domain\ValueObject;

use App\Domain\Exceptions\UserMustBeAboveMinYearsOldException;

readonly class Age
{
    private const MIN_AGE = 18;

    /**
     * @throws UserMustBeAboveMinYearsOldException
     */
    public function __construct(private int $value)
    {
        if ($value < self::MIN_AGE) {
            throw new UserMustBeAboveMinYearsOldException(self::MIN_AGE);
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
