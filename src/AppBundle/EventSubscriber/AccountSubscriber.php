<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Event\AccountEvent;
use AppBundle\Event\AccountEvents;
use AppBundle\Service\Notification\CreateNotificationService;
use AppBundle\Service\BitcoinWalletApiClient\Wallet;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountSubscriber implements EventSubscriberInterface
{
    protected $createNotificationService;
    protected $bitcoinWalletApiClient;

    public function __construct(
        CreateNotificationService $createNotificationService,
        Wallet $bitcoinWalletApiClient)
    {
        $this->bitcoinWalletApiClient = $bitcoinWalletApiClient;
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

    public function accountCreated(AccountEvent $event)
    {
        $this->createNotification($event);
        $this->createBitcoinWallet($event);
    }

    /**
     * Calls the bitcoin wallet in order to create the wallet for the account.
     * @param AccountEvent $event
     */
    private function createBitcoinWallet(AccountEvent $event)
    {
        $this->bitcoinWalletApiClient->createWallet(
            $event->getAccount()->getId(),
            $event->getAccount()->getForename().' '.$event->getAccount()->getLastname()
        );
    }

    /**
     * Calls the AppBundle\Service\Notification\CreateNotificationService whenever an
     * account is in order to create a pending iOS push notification for it.
     * @param AccountEvent $event
     */
    private function createNotification(AccountEvent $event)
    {
        $accountId = $event->getAccount()->getId();
        $deviceId = $event->getDeviceId();

        $this->createNotificationService->execute($accountId, $deviceId);
    }
}
