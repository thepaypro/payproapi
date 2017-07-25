<?php
namespace AppBundle\Service\MobileVerificationCode;

use libphonenumber\PhoneNumberUtil;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use AppBundle\Entity\MobileVerificationCode;
use AppBundle\Repository\MobileVerificationCodeRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Event\MobileVerificationCodeEvent;
use AppBundle\Event\MobileVerificationCodeEvents;
use AppBundle\Exception\PayProException;
use AppBundle\Service\PhoneNumberValidatorService;

/**
 * Class CreateMobileVerificationCodeService
 * @package AppBundle\Service
 */
class CreateMobileVerificationCodeService
{
    protected $dispatcher;
    protected $userRepository;
    protected $mobileVerificationCodeRepository;

    /**
     * @param EntityManager            $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        UserRepository $userRepository,
        MobileVerificationCodeRepository $mobileVerificationCodeRepository,
        PhoneNumberValidatorService $phoneNumberValidatorService,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->userRepository = $userRepository;
        $this->mobileVerificationCodeRepository = $mobileVerificationCodeRepository;
        $this->phoneNumberValidatorService = $phoneNumberValidatorService;
        $this->dispatcher = $eventDispatcher;
    }

    /**
     * This method will create a mobile verification code and dispatch an event of the creation.
     * 
     * @param  phoneNumber $phoneNumber
     * @return Array
     */
    public function execute(String $phoneNumber) : Array
    {
        if (!$this->phoneNumberValidatorService->isValid($phoneNumber)) {
            throw new PayProException("Invalid phone number", 400);
        }

        $user = $this->userRepository->findOneByUsername($phoneNumber);

        if ($user) {
            return ['isUser' => true]; 
        }

        $mobileVerificationCode = $this->mobileVerificationCodeRepository->findOneByPhoneNumber($phoneNumber);

        if (!$mobileVerificationCode) {
            $mobileVerificationCode = new MobileVerificationCode($phoneNumber);
            $this->mobileVerificationCodeRepository->save($mobileVerificationCode);
        }

        $this->dispatcher->dispatch(
            MobileVerificationCodeEvents::MOBILE_VERIFICATION_CODE_REQUESTED,
            new MobileVerificationCodeEvent($mobileVerificationCode)
        );

        return ['isUser' => false];
    }
}
