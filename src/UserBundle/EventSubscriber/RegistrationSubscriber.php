<?php

namespace UserBundle\EventSubscriber;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FormEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Service\User\Validator\UserValidatorService;
use AppBundle\Exception\PayProException;

class RegistrationSubscriber implements EventSubscriberInterface
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
            FOSUserEvents::REGISTRATION_FAILURE => [
                ['onRegistrationFailed', 0],
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

        if ($data['plainPassword']['first'] != $data['plainPassword']['second']) {
            throw new PayProException('Passwords dont match', 400);
        }

        $this->userValidatorService->validate($data['username'], $data['mobileVerificationCode']);
    }

    /**
     * Listener to set a response when the registration form fail.
     * @param  FormEvent $event
     */
    public function onRegistrationFailed(FormEvent $event)
    {
        $response = new JsonResponse($event->getForm()->getErrors()->__toString(), 400);

        $event->setResponse($response);
    }
}
