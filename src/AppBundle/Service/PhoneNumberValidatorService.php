<?php

namespace AppBundle\Service;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

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
     * @param  string  $phoneNumber
     * @return boolean
     */
    public function isValid(string $phoneNumber) : bool
    {
        try {
            $phoneNumberObject = $this->phoneUtil->parse($phoneNumber, null);
            return $this->phoneUtil->isValidNumber($phoneNumberObject);
        } catch (NumberParseException $e) {
            return false;
        }
    }
}
