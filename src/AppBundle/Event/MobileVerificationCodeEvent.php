<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\MobileVerificationCode;

class MobileVerificationCodeEvent extends Event
{
    protected $mobileVerificationCode;

    public function __construct(MobileVerificationCode $mobileVerificationCode)
    {
        $this->mobileVerificationCode = $mobileVerificationCode;
    }

    public function getMobileVerificationCode()
    {
        return $this->mobileVerificationCode;
    }
}
