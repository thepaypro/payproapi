<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Event\CardHolderVerificationEvent;
use AppBundle\Event\CardHolderVerificationEvents;
use AppBundle\Service\Notification\SendNotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CardHolderVerificationSubscriber implements EventSubscriberInterface
{
    protected $sendNotificationService;

    public function __construct(
        SendNotificationService $sendNotificationService)
    {
        $this->sendNotificationService = $sendNotificationService;
    }

    public static function getSubscribedEvents()
    {
        return [
            CardHolderVerificationEvents::CARD_HOLDER_VERIFICATION_COMPLETED=> [
                ['sendNotification', 0]
            ]
        ];
    }

    public function sendNotification(CardHolderVerificationEvent $event)
    {
        $message = $event->getMessage();
        $notification = $event->getNotification();

        $this->sendNotificationService->execute($message, $notification);
    }
}
