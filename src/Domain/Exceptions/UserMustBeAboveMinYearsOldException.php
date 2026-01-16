<?php

namespace App\Domain\Exceptions;

class UserMustBeAboveMinYearsOldException extends DomainException
{
    public function __construct(int $age)
    {
        parent::__construct(sprintf('User must be above %d years old.', $age));
    }
}
