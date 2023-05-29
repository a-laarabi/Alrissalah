<?php

namespace App\EventListeners;

use App\Entity\Payment;
use App\Entity\UserProgresses;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

class UserPaymentListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function postPersist(Payment $payment, PostPersistEventArgs $args)
    {
        $payment->setUser($this->security->getUser());
    }
}