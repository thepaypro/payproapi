<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\UserRepository;
use DateTime;

/**
 * Class IndexTransactionService
 */
class LastsTransactionService
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
     * @param int $transactionId
     * @param string fromDate
     * @param string toDate
     * @return array $transactions
     * @throws PayProException
     * @internal param int $payerId
     * @internal param int $beneficiaryId
     */
    public function execute(
        int $userId,
        int $transactionId,
        string $fromDate = null,
        string $toDate = null
    ): array
    {
        $user = $this->userRepository->findOneById($userId);
        $account = $user->getAccount();

        //TODO:check if transaction is owned by account
        $transaction = $this->transactionRepository->findOneById($transactionId);

        // TODO: Decide what should we do with the timestamps filters of the index.
        $fromDate = $transaction->getCreatedAt();

        $toDate = new DateTime();


        $this->contisSyncTransactionService->execute($account);

        $payProTransactions = $this->transactionRepository->getTransactionsOfAccountBetweenDates($account, $fromDate, $toDate);

        return $payProTransactions;
    }
}
