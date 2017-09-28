<?php

namespace AppBundle\Service\BitcoinTransaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\BitcoinWalletApiClient\Transaction;

/**
 * Class IndexBitcoinTransactionService
 */
class IndexBitcoinTransactionService
{
    protected $userRepository;
    protected $bitcoinTransactionApiClient;

    public function __construct(
        UserRepository $userRepository,
        Transaction $bitcoinTransactionApiClient
    )
    {
        $this->userRepository = $userRepository;
        $this->bitcoinTransactionApiClient = $bitcoinTransactionApiClient;
    }

    /**
     * @param int $userId
     * @param int $page
     * @param int $size
     * @return array
     * @throws PayProException
     */
    public function execute(
        int $userId,
        int $page,
        int $size
    ): array
    {
        $user = $this->userRepository->findOneById($userId);

        $bitcoinTransactions = $this->bitcoinTransactionApiClient->getAll($user->getAccount()->getId());

        $bitcoinTransactions = array_slice($bitcoinTransactions, $page, $size);

        return $bitcoinTransactions;
    }
}
