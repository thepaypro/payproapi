<?php

namespace AppBundle\Service\BitcoinWallet;

use AppBundle\Repository\UserRepository;
use AppBundle\Service\BitcoinWalletApiClient\Wallet;
use AppBundle\Exception\PayProException;

/**
 * Class GetBitcoinWalletService
 */
class GetBitcoinWalletService
{
    protected $userRepository;
    protected $bitcoinWalletApiClient;

    public function __construct(
        UserRepository $userRepository,
        Wallet $bitcoinWalletApiClient
    )
    {
        $this->userRepository = $userRepository;
        $this->bitcoinWalletApiClient = $bitcoinWalletApiClient;
    }

    /**
     * @param int $userId
     * @return array
     * @throws PayProException
     */
    public function execute(int $userId): array
    {
        $user = $this->userRepository->findOneById($userId);

        $bitcoinTransactions = $this->bitcoinWalletApiClient->getOne($user->getAccount()->getId());

        return $bitcoinTransactions;
    }
}
