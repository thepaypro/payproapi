<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use AppBundle\Event\CardActivationCodeEvent;
use AppBundle\Event\CardActivationCodeEvents;
use AppBundle\Service\TwilioShortMessageService;

class CardActivationCodeSubscriber implements EventSubscriberInterface
{
	protected $shortMessageService;

	public function __construct(
        TwilioShortMessageService $shortMessageService
        )
    {
        $this->shortMessageService = $shortMessageService;
    }

    public static function getSubscribedEvents() : array
    {
        return array(
            CardActivationCodeEvents::CARD_ACTIVATION_CODE_REQUESTED => array('sendCardActivationCode', 0)
        );
    }

    /**
     * This method will send the card activation code to the user by sms
     * 
     * @param  CardActivationCodeEvent $event Event with the Account.
     */
    public function sendCardActivationCode(CardActivationCodeEvent $event)
    {    
        dump($event->getPhoneNumber()).die();
        $this->shortMessageService->sendShortMessage(
            $event->getPhoneNumber(),
            $event->getCardActivationCode()
        );
    }
}
