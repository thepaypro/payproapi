<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use AppBundle\Event\MobileVerificationCodeEvent;
use AppBundle\Event\MobileVerificationCodeEvents;
use AppBundle\Service\TwilioShortMessageService;

class MobileVerificationCodeSubscriber implements EventSubscriberInterface
{
    protected $shortMessageService;

    public function __construct(TwilioShortMessageService $shortMessageService)
    {
        $this->shortMessageService = $shortMessageService;
    }

    public static function getSubscribedEvents() : Array
    {
        return array(
            MobileVerificationCodeEvents::MOBILE_VERIFICATION_CODE_REQUESTED => array('sendMobileVerificationCode', 0)
        );
    }

    /**
     * @param  MobileVerificationCodeEvent $event Event with the mobileVerificationCode created.
     */
    public function sendMobileVerificationCode(MobileVerificationCodeEvent $event)
    {
        $mobileVerificationCode = $event->getMobileVerificationCode();

        $this->shortMessageService->sendShortMessage(
            $mobileVerificationCode->getPhoneNumber(),
            $mobileVerificationCode->getCode()
        );
    }
}
