<?php

namespace AppBundle\Service\Account;

use AppBundle\Entity\Account;
use AppBundle\Event\AccountEvent;
use AppBundle\Event\CardHolderVerificationEvent;
use AppBundle\Event\CardHolderVerificationEvents;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\AgreementRepository;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\ContisApiClient\Account as ContisAccountApiClient;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CreateAccountService
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

            $cardHolder = $this->getContainer()
                ->contisAccountApiClient
                ->getOne($account->getCardHolderId());

            $deviceId = $account->getNotification()->getDeviceId();

            $this->dispatcher->dispatch(
                CardHolderVerificationEvents::CARD_HOLDER_VERIFICATION_COMPLETED,
                new CardHolderVerificationEvent($cardHolder, $deviceId)
            );
        }
    }
}
