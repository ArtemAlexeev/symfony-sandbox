<?php

namespace App\Domain\ValueObject;

use App\Domain\Exceptions\UserMustBeAboveMinYearsOldException;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Embeddable]
readonly class Age implements Stringable
{
    private const MIN_AGE = 18;

    #[ORM\Column(name: "age", type: "integer", nullable: true)]
    private ?int $value;

    /**
     * @throws UserMustBeAboveMinYearsOldException
     */
    public function __construct(?int $value)
    {
        if ($value !== null && $value < self::MIN_AGE) {
            throw new UserMustBeAboveMinYearsOldException(self::MIN_AGE);
        }

        $this->value = $value;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string) ($this->value ?? '');
    }
}
