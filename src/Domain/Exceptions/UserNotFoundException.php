<?php

namespace App\Domain\Exceptions;

class UserNotFoundException extends DomainException
{
    public function __construct(int $userId)
    {
        parent::__construct(sprintf('User with ID %d could not be found.', $userId), 404);
    }
}
