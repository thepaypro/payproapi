<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    private $appVersionGetAllService;

    public function __construct($appVersionGetAllService) {
        $this->appVersionGetAllService = $appVersionGetAllService;
    }

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
                ['user' => $user->jsonSerialize()],
                ['version' => $this->appVersionGetAllService->execute()]
            )
        );
    }
}
