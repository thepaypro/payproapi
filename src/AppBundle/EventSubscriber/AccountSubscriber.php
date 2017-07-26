<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Event\AccountEvent;
use AppBundle\Event\AccountEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AccountSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            AccountEvents::ACCOUNT_CREATED => [
                ['createNotification', 0]
            ]
        ];
    }

    public function createNotification(AccountEvent $event)
    {
        $account = $event->getAccount();

        //TODO: Add CreateNotificationService instance here.
    }
}
