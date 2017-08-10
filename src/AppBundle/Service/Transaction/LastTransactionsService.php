<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\UserRepository;


/**
 * Class LastTransactionsService
 */
class LastTransactionsService
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
     * This method retrieves all the transactions after a given transaction timestamp.
     *
     * @param  int $userId
     * @param int $transactionId
     * @param string fromDate
     * @param string toDate
     * @return array $transactions
     * @throws PayProException
     */
    public function execute(
        int $userId,
        int $transactionId = null
    ): array
    {
        $user = $this->userRepository->findOneById($userId);
        $account = $user->getAccount();
        $accountIsBeneficiary = false;
        $accountIsPayer = false;

        $transaction = $this->transactionRepository->findOneById($transactionId);
        if (!$account) {
            throw new PayProException("invalid token", 400);
        }
        if (!$transaction || !($transaction->getPayer() || $transaction->getBeneficiary())) {
            throw new PayProException("invalid transactionId", 400);
        }
        if ($transaction->getPayer()) {
            $accountIsPayer = ($transaction->getPayer()->getId() == $account->getId());
        }
        if ($transaction->getBeneficiary()) {
            $accountIsBeneficiary = ($transaction->getBeneficiary()->getId() == $account->getId());
        }
        if (!$accountIsBeneficiary && !$accountIsPayer) {
            throw new PayProException("invalid transactionId", 400);
        }

        $this->contisSyncTransactionService->execute($account);

        $payProTransactions = $this->transactionRepository->getTransactionsOfAccountAfterTransactionId($account, $transaction->getId());

        return $payProTransactions;
    }
}
