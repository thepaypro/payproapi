<?php

namespace AppBundle\Service\Notification;

use AppBundle\Entity\Notification;
use RMS\PushNotificationsBundle\Message\iOSMessage;
use RMS\PushNotificationsBundle\Service\iOSFeedback;
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
     * Receives a notification and a message, creates an iOS push notification
     * and if its successfully sent updates the notification to reflect the fact.
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

    public function sendCardHolderVerifiedNotification(
        string $statusCode,
        Notification $notification)
    {
        if ($statusCode == '01') {
            $message = 'Your account application has been approved. You can now fund it and order your Visa Card.';
        }
        if ($statusCode == '09') {
            $message = 'Your account aplication needs further information, please contact us on Support.';
        }
        if ($statusCode == '07') {
            $message = 'We are sorry to inform that your account application has not been approved.';
        }

        $this->execute($message, $notification);
    }
}
