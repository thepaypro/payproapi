<?php

namespace AppBundle\Service\Notification;

use AppBundle\Entity\Notification;
use RMS\PushNotificationsBundle\Message\iOSMessage;
use RMS\PushNotificationsBundle\Service\Notifications as PushNotifications;

/**
 * Class CreateNotificationService
 */
class SendNotificationService
{
    protected $pushNotifications;
    protected $updateNotificationService;

    /**
     * SendNotificationService constructor.
     * @param PushNotifications $pushNotifications
     * @param UpdateNotificationService $updateNotificationService
     */
    public function __construct(
        PushNotifications $pushNotifications,
        UpdateNotificationService $updateNotificationService)
    {
        $this->pushNotifications = $pushNotifications;
        $this->updateNotificationService = $updateNotificationService;
    }

    /**
     * @param string $message
     * @param Notification $notification
     */
    public function execute(
        string $message,
        Notification $notification)
    {
        $pushNotification = new iOSMessage();
        $pushNotification->setMessage($message);
        $pushNotification->setDeviceIdentifier($notification->getDeviceId());

        $this->pushNotifications->send($pushNotification);

        $this->updateNotificationService->execute(
            $notification->getId(),
            true,
            $notification->getAccount()->getId(),
            $notification->getDeviceId());
    }
}
