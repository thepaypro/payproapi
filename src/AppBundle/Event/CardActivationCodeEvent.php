<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Account;
use Symfony\Bridge\Monolog\Logger;

class CardActivationCodeEvent extends Event
{
	protected $account;
	protected $logger;

	public function __construct(
		Account $account,
		Logger $logger
		)
	{
		$this->account = $account;
		$logger->info('CardActivationCodeEvent');
	}

	/**
	 * @return Account
	 */
	public function getAccount(): Account
	{
		return $this->account;
	}
}