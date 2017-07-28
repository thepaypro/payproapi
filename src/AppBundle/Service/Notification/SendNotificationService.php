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
    protected $feedbackNotifications;
    protected $updateNotificationService;

    /**
     * SendNotificationService constructor.
     * @param PushNotifications $pushNotifications
     * @param iOSFeedback $feedbackNotifications
     * @param UpdateNotificationService $updateNotificationService
     */
    public function __construct(
        PushNotifications $pushNotifications,
        iOSFeedback $feedbackNotifications,
        UpdateNotificationService $updateNotificationService)
    {
        $this->pushNotifications = $pushNotifications;
        $this->feedbackNotifications = $feedbackNotifications;
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
        $this->feedbackNotifications->getDeviceUUIDs();

        $this->updateNotificationService->execute(
            $notification->getId(),
            true,
            $notification->getAccount()->getId(),
            $notification->getDeviceId());
    }
}
