<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Event\AccountEvent;
use AppBundle\Event\AccountEvents;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\WalletInterface;
use AppBundle\Service\Notification\CreateNotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Service\BitcoinAccount\CreateBitcoinAccountService;

class AccountSubscriber implements EventSubscriberInterface
{
    protected $createNotificationService;
    protected $bitcoinWalletApiClient;
    protected $createBitcoinAccountService;

    public function __construct(
        CreateNotificationService $createNotificationService,
        WalletInterface $bitcoinWalletApiClient,
        CreateBitcoinAccountService $createBitcoinAccountService
    )
    {
        $this->bitcoinWalletApiClient = $bitcoinWalletApiClient;
        $this->createNotificationService = $createNotificationService;
        $this->createBitcoinAccountService = $createBitcoinAccountService;
    }

    public static function getSubscribedEvents()
    {
        return [
            AccountEvents::ACCOUNT_CREATED => [
                ['accountCreated', 0]
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

        $this->bitcoinWalletApiClient->create(
            $event->getAccount()->getId(),
            $event->getAccount()->getForename().' '.$event->getAccount()->getLastname()
        );

        $wallet = $this->bitcoinWalletApiClient->getOne(
            $event->getAccount()->getId()
        );

        $this->createBitcoinAccountService->execute(
            $event->getUser()->getId(),
            $wallet['address'],
            $event->getDeviceToken()
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
