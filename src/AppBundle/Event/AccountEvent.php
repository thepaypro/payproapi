<?php

namespace AppBundle\Event;

use AppBundle\Entity\Account;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class AccountEvent extends Event
{
    protected $user;
    protected $account;
    protected $deviceToken;

    public function __construct(User $user, Account $account, string $deviceToken)
    {
        $this->user = $user;
        $this->account = $account;
        $this->deviceToken = $deviceToken;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
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
    public function getDeviceToken(): string
    {
        return $this->deviceToken;
    }
}
