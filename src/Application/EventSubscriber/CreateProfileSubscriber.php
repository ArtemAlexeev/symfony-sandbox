<?php

namespace App\Application\EventSubscriber;

use App\Domain\Entity\Profile;
use App\Domain\Event\UserRegisteredEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CreateProfileSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered',
        ];
    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $profile = new Profile();
        $profile->setUser($event->user);
        $this->entityManager->persist($profile);
        $this->entityManager->flush();
    }
}
