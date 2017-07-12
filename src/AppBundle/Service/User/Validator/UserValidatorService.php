<?php

namespace AppBundle\Service\User\Validator;

use AppBundle\Repository\MobileVerificationCodeRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\PhoneNumberValidatorService;
use AppBundle\Exception\PayProException;

class UserValidatorService
{
    private $mobileVerificationCodeRepository;
    private $userRepository;
    private $PhoneNumberValidator;

    public function __construct(
        MobileVerificationCodeRepository $mobileVerificationCodeRepository,
        UserRepository $userRepository,
        PhoneNumberValidatorService $phoneNumberValidator
    )
    {
        $this->mobileVerificationCodeRepository = $mobileVerificationCodeRepository;
        $this->userRepository = $userRepository;
        $this->phoneNumberValidator = $phoneNumberValidator;
    }

    /**
     * Validates that a user with the given phone number and validation code can be created.
     * 
     * @param  String $phoneNumber
     * @param  String $verificationCode
     */
    public function validate(String $phoneNumber, String $verificationCode) : bool
    {
        if (!$this->phoneNumberValidator->isValid($phoneNumber)) {
            throw new PayProException('Invalid phone number', 400);
        }

        if ($this->userRepository->findOneByUsername($phoneNumber)) {
            throw new PayProException('Username already exist', 400);
        }

        $mobileVerificationCode = $this->mobileVerificationCodeRepository->findOneBy([
            'code' => $verificationCode,
            'phoneNumber' => $phoneNumber
        ]);

        if (!$mobileVerificationCode) {
            throw new PayProException('Invalid verification code', 400);
        }

        return true;
    }
}
