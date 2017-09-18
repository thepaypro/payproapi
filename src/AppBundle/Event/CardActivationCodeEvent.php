<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CardActivationCodeEvent extends Event
{
	protected $phoneNumber;
	protected $cardActivationCode;

	public function __construct(
		String $phoneNumber,
		string $cardActivationCode
		)
	{
		$this->phoneNumber = $phoneNumber;
		$this->cardActivationCode = $cardActivationCode;
	}

	/**
	 * @return String
	 */
	public function getCardActivationCode(): String
	{
		return $this->cardActivationCode;
	}	

	/**
	 * @return String
	 */
	public function getPhoneNumber(): String
	{
		return $this->phoneNumber;
	}
}