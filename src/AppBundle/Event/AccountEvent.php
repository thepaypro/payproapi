<?php

namespace AppBundle\Event;

use AppBundle\Entity\Account;
use Symfony\Component\EventDispatcher\Event;

class AccountEvent extends Event
{
    protected $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }
}
