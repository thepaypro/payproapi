<?php

namespace AppBundle\Service\CardHolder;

use AppBundle\Entity\Account;
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
        array $accounts)
    {
        foreach ($accounts as $key => $account) {

            $cardHolder = $this->contisAccountApiClient->getOne($account->getCardHolderId());

            if (in_array($cardHolder['Status'], $this->contisAccountApiClient->getContisStatuses())) {
                $account->setStatus(
                    $this->contisAccountApiClient->getAccountStatusFromContisStatus($cardHolder['Status'])
                );

                $this->dispatcher->dispatch(
                    CardHolderVerificationEvents::CARD_HOLDER_VERIFICATION_COMPLETED,
                    new CardHolderVerificationEvent($account)
                );
            }
        }
    }
}
