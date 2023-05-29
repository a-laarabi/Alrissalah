<?php

namespace App\EventListeners;

use App\Entity\UserProgresses;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
class UserProgressPersistentListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function postPersist(UserProgresses $userProgresses, PostPersistEventArgs $args)
    {
        $userProgresses->setUser($this->security->getUser());
    }
}