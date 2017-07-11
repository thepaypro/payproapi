<?php

namespace AppBundle\Service;

use libphonenumber\PhoneNumberUtil;

/**
 * Class PhoneNumberValidatorService
 * @package AppBundle\Service
 */
class PhoneNumberValidatorService
{
    protected $phoneUtil;

    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Validates the specified phone number.
     * @param  String  $phoneNumber
     * @return boolean
     */
    private function isValid(String $phoneNumber) : Boolean
    {
        try {
            $phoneNumberObject = $this->phoneUtil->parse($phoneNumber, null);
            return $this->phoneUtil->isValidNumber($phoneNumberObject);
        } catch (NumberParseException $e) {
            return false;
        }
    }
}
