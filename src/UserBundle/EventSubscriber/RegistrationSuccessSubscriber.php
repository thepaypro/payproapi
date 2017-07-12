<?php

namespace UserBundle\EventSubscriber;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use AppBundle\Service\User\Validator\UserValidatorService;

class RegistrationSuccessSubscriber implements EventSubscriberInterface
{
    private $userValidatorService;

    public function __construct(UserValidatorService $userValidatorService)
    {
        $this->userValidatorService = $userValidatorService;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): Array
    {
        return [
            FOSUserEvents::REGISTRATION_INITIALIZE => [
                ['onRegistrationInitialize', -10],
            ],
        ];
    }

    /**
     * Listener to check that user phoneNumber is valid and user validationCode is correct.
     * @param  GetResponseUserEvent $event
     */
    public function onRegistrationInitialize(GetResponseUserEvent $event)
    {
        $data = $event->getRequest()->request->all()['app_user_registration'];

        $this->userValidatorService->validate($data['username'], $data['validationCode']);
    }
}
