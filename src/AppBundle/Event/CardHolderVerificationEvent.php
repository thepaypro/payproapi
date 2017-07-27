<?php

namespace AppBundle\Event;

use AppBundle\Entity\Account;
use Symfony\Component\EventDispatcher\Event;

class CardHolderVerificationEvent extends Event
{
    protected $message;
    protected $deviceId;

    public function __construct(String $message, string $deviceId)
    {
        $this->message = $message;
        $this->deviceId = $deviceId;
    }

    /**
     * @return String
     */
    public function getMessage(): String
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getDeviceId(): string
    {
        return $this->deviceId;
    }
}
