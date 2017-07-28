<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Event\AccountEvent;
use AppBundle\Event\AccountEvents;
use AppBundle\Service\Notification\CreateNotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountSubscriber implements EventSubscriberInterface
{
    protected $createNotificationService;

    public function __construct(
        CreateNotificationService $createNotificationService)
    {
        $this->createNotificationService = $createNotificationService;
    }

    public static function getSubscribedEvents()
    {
        return [
            AccountEvents::ACCOUNT_CREATED => [
                ['createNotification', 0]
            ]
        ];
    }

    /**
     * Calls the AppBundle\Service\Notification\CreateNotificationService whenever an
     * account is in order to create a pending iOS push notification for it.
     * @param AccountEvent $event
     */
    public function createNotification(AccountEvent $event)
    {
        $accountId = $event->getAccount()->getId();
        $deviceId = $event->getDeviceId();

        $this->createNotificationService->execute($accountId, $deviceId);
    }
}
