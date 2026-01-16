<?php

namespace App\Infrastructure\Entrypoints\Http;

use App\Domain\Entity\User;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    public function getUser(): ?User
    {
        $user = parent::getUser();

        if (null === $user) {
            return null;
        }

        if (!$user instanceof User) {
            throw new LogicException('The logged-in user is not an instance of the App User entity.');
        }

        return $user;
    }
}
