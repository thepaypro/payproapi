<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\UserRepository;
use DateTime;

/**
 * Class IndexTransactionService
 */
class IndexTransactionService
{
    protected $transactionRepository;
    protected $userRepository;
    protected $contisSyncTransactionService;

    /**
     * @param TransactionRepository $transactionRepository
     * @param UserRepository $userRepository
     * @param ContisSyncTransactionService $contisSyncTransactionService
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        UserRepository $userRepository,
        ContisSyncTransactionService $contisSyncTransactionService
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
        $this->contisSyncTransactionService = $contisSyncTransactionService;
    }

    /**
     * This method will retrieve all the transactions from the database and from Contis and will merge them.
     *
     * @param  int $userId
     * @param int $page
     * @param int $size
     * @param  string $fromDate
     * @param  string $toDate
     * @return array $transactions
     * @throws PayProException
     * @internal param int $payerId
     * @internal param int $beneficiaryId
     */
    public function execute(
        int $userId,
        int $page = 0,
        int $size = 10,
        string $fromDate = null,
        string $toDate = null
    ): array
    {
        $user = $this->userRepository->findOneById($userId);
        $account = $user->getAccount();

        if (!is_int($page)) {
            throw new PayProException("Invalid page format.", 400);
        }

        if (!is_int($size)) {
            throw new PayProException("Invalid size format.", 400);
        }

        // TODO: Decide what should we do with the timestamps filters of the index.
        if (!$fromDate) {
            $fromDate = $account->getCreatedAt();
        }

        if (!$toDate) {
            $toDate = new DateTime();
        }

        $this->contisSyncTransactionService->execute($account);

        $payProTransactions = $this->transactionRepository->getTransactionsOfAccount($account, $page, $size);

        return $payProTransactions;
    }
}
