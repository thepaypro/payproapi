<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use AppBundle\Event\CardActivationCodeEvent;
use AppBundle\Event\CardActivationCodeEvents;
use AppBundle\Service\TwilioShortMessageService;
use Symfony\Bridge\Monolog\Logger;

class CardActivationCodeSubscriber implements EventSubscriberInterface
{
	protected $shortMessageService;
    protected $logger;

	public function __construct(
        TwilioShortMessageService $shortMessageService
        Logger $logger

        )
    {
        $this->shortMessageService = $shortMessageService;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents() : array
    {
        return array(
            CardActivationCodeEvents::CARD_ACTIVATION_CODE_REQUESTED => array('sendCardActivationCode', 0)
        );
        $this->logger->info('getSubscribedEvents::::CARD_ACTIVATION_CODE_REQUESTED');
    }

    /**
     * This method will send the card activation code to the user by sms
     * 
     * @param  CardActivationCodeEvent $event Event with the Account.
     */
    public function sendCardActivationCode(CardActivationCodeEvent $event)
    {
        $this->logger->info('Sending card Activation Code to user by sms');

    	$account = $event->getAccount();
        $cardActivationCode = $account->getCard()->getContisCardActivationCode();
        
        $this->shortMessageService->sendShortMessage(
            $account->getUsers()[0]->getUsername(),
            $cardActivationCode
        );
    }
}
