<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Event\CardHolderVerificationEvent;
use AppBundle\Event\CardHolderVerificationEvents;
use AppBundle\Repository\AccountRepository;
use AppBundle\Service\Notification\SendNotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CardHolderVerificationSubscriber implements EventSubscriberInterface
{
    protected $sendNotificationService;
    protected $accountRepository;

    /**
     * CardHolderVerificationSubscriber constructor.
     * @param SendNotificationService $sendNotificationService
     * @param AccountRepository $accountRepository
     */
    public function __construct(
        SendNotificationService $sendNotificationService,
        AccountRepository $accountRepository)
    {
        $this->sendNotificationService = $sendNotificationService;
        $this->accountRepository = $accountRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            CardHolderVerificationEvents::CARD_HOLDER_VERIFICATION_COMPLETED=> [
                ['sendNotification', 0],
                ['updateAccountStatus', 0]
            ]
        ];
    }

    /**
     * Receives a CardHolderVerificationEvent and calls the SendNotificationService
     * in order to send an iOS notification with said event.
     * @param CardHolderVerificationEvent $event
     */
    public function sendNotification(CardHolderVerificationEvent $event)
    {
        $status = $event->getAccount()->getStatus();
        $notification = $event->getAccount()->getNotification();
        $this->sendNotificationService->sendCardHolderVerifiedNotification($status, $notification);
    }

    public function updateAccountStatus(CardHolderVerificationEvent $event) {
        $this->accountRepository->save($event->getAccount());
    }
}