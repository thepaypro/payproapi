<?php

namespace AppBundle\Service\Notification;

use AppBundle\Entity\Notification;
use RMS\PushNotificationsBundle\Message\iOSMessage;
use Symfony\Component\Translation\TranslatorInterface;
use RMS\PushNotificationsBundle\Service\Notifications as PushNotifications;

/**
 * Class CreateNotificationService
 */
class SendNotificationService
{
    protected $pushNotifications;
    protected $updateNotificationService;
    protected $translator;

    /**
     * SendNotificationService constructor.
     * @param PushNotifications $pushNotifications
     * @param UpdateNotificationService $updateNotificationService
     * @param TranslatorInterface $translator
     */
    public function __construct(
        PushNotifications $pushNotifications,
        UpdateNotificationService $updateNotificationService,
        TranslatorInterface $translator)
    {
        $this->pushNotifications = $pushNotifications;
        $this->updateNotificationService = $updateNotificationService;
        $this->translator = $translator;
    }

    /**
     * Receives a notification and a message, creates an iOS push notification
     * and if its successfully sent updates the notification to reflect the fact.
     * @param string $message
     * @param Notification $notification
     */
    private function execute(
        string $message,
        Notification $notification)
    {
        $pushNotification = new iOSMessage();
        $pushNotification->setMessage($message);
        $pushNotification->setData(['account' => json_encode($notification->getAccount()->jsonSerialize())]);
        $pushNotification->setDeviceIdentifier($notification->getDeviceId());

        $this->pushNotifications->send($pushNotification);

        $this->updateNotificationService->execute(
            $notification->getId(),
            true,
            $notification->getAccount()->getId(),
            $notification->getDeviceId());
    }

    /**
     * @param string $status
     * @param Notification $notification
     */
    public function sendCardHolderVerifiedNotification(
        string $status,
        Notification $notification)
    {
        $message = $this->translator->trans('account_created_notifications.' . $status, [], 'notifications');
        $this->execute($message, $notification);
    }
}
