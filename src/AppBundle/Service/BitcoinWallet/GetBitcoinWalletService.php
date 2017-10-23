<?php

namespace AppBundle\Service\BitcoinWallet;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\WalletInterface;

/**
 * Class GetBitcoinWalletService
 */
class GetBitcoinWalletService
{
    protected $userRepository;
    protected $bitcoinWalletApiClient;

    public function __construct(
        UserRepository $userRepository,
        WalletInterface $bitcoinWalletApiClient
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

        if (!$bitcoinAccount = $user->getBitcoinAccount()) {
            throw new PayProException('Bitcoin account not found', 404);
        }

        $bitcoinWallet = $this->bitcoinWalletApiClient->getOne($user->getAccount()->getId());
        $user->getBitcoinAccount()->setBalance($bitcoinWallet['balance']);
        return $bitcoinWallet;
    }
}
