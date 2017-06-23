<?php

namespace UserBundle\EventSubscriber;

use libphonenumber\PhoneNumberUtil;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Repository\MobileVerificationCodeRepository;
use AppBundle\Repository\UserRepository;

class RegistrationSuccessSubscriber implements EventSubscriberInterface
{
    private $mobileVerificationCodeRepository;
    private $userRepository;

    public function __construct(
        MobileVerificationCodeRepository $mobileVerificationCodeRepository,
        UserRepository $userRepository
    )
    {
        $this->mobileVerificationCodeRepository = $mobileVerificationCodeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
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

        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try {
            $phoneNumberObject = $phoneNumberUtil->parse($data['username'], null);
        } catch (\Exception $e) {
            return $event->setResponse(new JsonResponse(
                [
                    'statusCode' => 400,
                    'message' => $e->getMessage()
                ]
            ));
        }

        if ($this->userRepository->findOneByUsername($data['username'])) {
            return $event->setResponse(new JsonResponse(
                [
                    'statusCode' => 400,
                    'message' => 'Username already exist'
                ]
            ));
        }

        $mobileVerificationCode = $this->mobileVerificationCodeRepository->findOneBy([
            'code' => $data['validationCode'],
            'phoneNumber' => $data['username']
        ]);

        if (!$mobileVerificationCode) {
            return $event->setResponse(new JsonResponse(
                [
                    'statusCode' => 400,
                    'message' => 'Invalid verification code'
                ]
            ));
        }
    }
}
