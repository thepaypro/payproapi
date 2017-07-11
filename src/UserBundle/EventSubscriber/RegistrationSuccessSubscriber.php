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
    private $PhoneNumberValidator;

    public function __construct(
        MobileVerificationCodeRepository $mobileVerificationCodeRepository,
        UserRepository $userRepository,
        PhoneNumberValidatorService $PhoneNumberValidator
    )
    {
        $this->mobileVerificationCodeRepository = $mobileVerificationCodeRepository;
        $this->userRepository = $userRepository;
        $this->phoneNumberValidator = $phoneNumberValidator;
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

        if (!$this->phoneNumberValidator->isValid($data['username'])) {
            throw new \Exception('Invalid phone number', 400);
        }

        if ($this->userRepository->findOneByUsername($data['username'])) {
            throw new \Exception('Username already exist', 400);
        }

        $mobileVerificationCode = $this->mobileVerificationCodeRepository->findOneBy([
            'code' => $data['validationCode'],
            'phoneNumber' => $data['username']
        ]);

        if (!$mobileVerificationCode) {
            throw new \Exception('Invalid verification code', 400);
        }
    }
}
