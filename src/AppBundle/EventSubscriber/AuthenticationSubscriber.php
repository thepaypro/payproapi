<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use UserInterface;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Events::AUTHENTICATION_SUCCESS => [
                ['addUserToResponse', 10]
            ]
        ];
    }

    public function addUserToResponse(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();

        $event->setData(
            array_merge(
                $event->getData(),
                ["user" => $user->jsonSerialize()]
            )
        );
    }
}
