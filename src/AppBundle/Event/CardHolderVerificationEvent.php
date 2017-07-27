<?php

namespace AppBundle\Event;

use AppBundle\Entity\Account;
use AppBundle\Entity\Notification;
use Symfony\Component\EventDispatcher\Event;

class CardHolderVerificationEvent extends Event
{
    protected $message;
    protected $notification;

    public function __construct(String $message, Notification $notification)
    {
        $this->message = $message;
        $this->notification = $notification;
    }

    /**
     * @return String
     */
    public function getMessage(): String
    {
        return $this->message;
    }

    /**
     * @return Notification
     */
    public function getNotification(): Notification
    {
        return $this->notification;
    }
}
