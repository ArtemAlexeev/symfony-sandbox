<?php

namespace App\Domain\Exceptions;

class SelfReactionException extends DomainException
{
    public function __construct()
    {
        parent::__construct('You are not allowed to react to your own profile.', 400);
    }
}
