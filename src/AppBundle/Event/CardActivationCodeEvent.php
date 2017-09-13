<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CardActivationCodeEvent extends Event
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
		return $this->account
	}
}