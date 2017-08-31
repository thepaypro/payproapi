<?php

namespace AppBundle\Event;

use AppBundle\Entity\Account;
use Symfony\Component\EventDispatcher\Event;

class AccountEvent extends Event
{
    protected $account;
    protected $deviceId;

    public function __construct(Account $account, string $deviceId)
    {
        $this->account = $account;
        $this->deviceId = $deviceId;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getDeviceId(): string
    {
        return $this->deviceId;
    }
}
