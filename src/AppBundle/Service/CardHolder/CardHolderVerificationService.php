<?php

namespace AppBundle\Service\CardHolder;

use AppBundle\Event\CardHolderVerificationEvent;
use AppBundle\Event\CardHolderVerificationEvents;
use AppBundle\Service\ContisApiClient\Account as ContisAccountApiClient;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CardHolderVerificationService
 */
class CardHolderVerificationService
{
    protected $contisAccountApiClient;
    protected $dispatcher;

    /**
     * CardHolderVerificationService constructor.
     * @param ContisAccountApiClient $contisAccountApiClient
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ContisAccountApiClient $contisAccountApiClient,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->contisAccountApiClient = $contisAccountApiClient;
        $this->dispatcher = $eventDispatcher;
    }

    /**
     * @param array $accounts
     */
    public function execute(
        Array $accounts)
    {
        foreach ($accounts as $key => $account) {

            $cardHolder = $this->contisAccountApiClient->getOne($account->getCardHolderId());

            $deviceId = $account->getNotification()->getDeviceId();

            //TODO: Add conditions of verification of CardHolder Contis account.
            $this->dispatcher->dispatch(
                CardHolderVerificationEvents::CARD_HOLDER_VERIFICATION_COMPLETED,
                new CardHolderVerificationEvent($cardHolder, $deviceId)
            );
        }
    }
}
