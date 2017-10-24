<?php

namespace AppBundle\Service\BitcoinTransaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\UserRepository;

/**
 * Class IndexBitcoinTransactionService
 */
class IndexBitcoinTransactionService
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

        if (!is_int($page)) {
            throw new PayProException("Invalid page format.", 400);
        }

        if (!is_int($size)) {
            throw new PayProException("Invalid size format.", 400);
        }

        $this->bitcoinSyncTransactionService->execute($user->getBitcoinAccount()); 

        return $bitcoinTransactions;
    }
}
