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

        $message = $this->translator->trans('account_created_notifications.'.$statusCode, [], 'notifications', 'en');
        dump($message);die();
        $this->execute($message, $notification);
    }
}
