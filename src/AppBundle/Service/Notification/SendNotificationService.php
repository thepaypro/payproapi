<?php

namespace AppBundle\Service\Notification;

use RMS\PushNotificationsBundle\Message\iOSMessage;
use RMS\PushNotificationsBundle\Service\Notifications;

/**
 * Class CreateNotificationService
 */
class SendNotificationService
{
    protected $notifications;

    /**
     * SendNotificationService constructor.
     * @param $notifications
     */
    public function __construct(Notifications $notifications)
    {
        $this->notifications = $notifications;
    }

    public function execute(
        String $message,
        String $deviceId)
    {
        $pushNotification = new iOSMessage();
        $pushNotification->setMessage($message);
        $pushNotification->setDeviceIdentifier($deviceId);

        $this->notifications->send($pushNotification);
    }
}
