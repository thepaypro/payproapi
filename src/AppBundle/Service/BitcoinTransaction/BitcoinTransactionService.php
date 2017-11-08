<?php

namespace AppBundle\Service\BitcoinTransaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\BitcoinTransactionRepository;
use AppBundle\Repository\UserRepository;

/**
 * Class BitcoinTransactionService
 */
class BitcoinTransactionService
{
    protected $bitcoinTransactionRepository;
    protected $userRepository;
    protected $bitcoinSyncTransactionService;

    /**
     *
     * @param BitcoinTransactionRepository $bitcoinTransactionRepository   
     * @param UserRepository $userRepository
     * @param BitcoinSyncTransactionService  $bitcoinSyncTransactionService   
     */
    public function __construct(
        BitcoinTransactionRepository $bitcoinTransactionRepository,
        UserRepository $userRepository,
        BitcoinSyncTransactionService $bitcoinSyncTransactionService
    )
    {
        $this->bitcoinTransactionRepository = $bitcoinTransactionRepository;
        $this->userRepository = $userRepository;
        $this->bitcoinSyncTransactionService = $bitcoinSyncTransactionService;
    }

    /**
     * This method will retrieve all the transactions from the database and from Blockchain and will merge them.
     * @param int $userId
     * @return array
     * @throws PayProException
     */
    public function execute(
        int $userId
    ): array
    {
        $user = $this->userRepository->findOneById($userId);

        $this->bitcoinSyncTransactionService->execute($user); 

       $bitcoinTransactions = $this->bitcoinTransactionRepository->getAllTransactionsOfAccount(
            $user->getBitcoinAccount()
        );

       // dump($bitcoinTransactions);die();

        return $bitcoinTransactions;
    }
}
