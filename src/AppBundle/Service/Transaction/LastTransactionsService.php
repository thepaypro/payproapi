<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\UserRepository;
use DateTime;

/**
 * Class IndexTransactionService
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
     * @internal param int $payerId
     * @internal param int $beneficiaryId
     */
    public function execute(
        int $userId,
        int $transactionId,
        string $fromDate = null
    ): array
    {
        $user = $this->userRepository->findOneById($userId);
        $account = $user->getAccount();

        //TODO: check ownership of given transaction
        $transaction = $this->transactionRepository->findOneById($transactionId);
//        if ($transaction->getPayer() != $account->getId() && $transaction->getBeneficiary() != $account->getId()) {
//            throw new PayProException("invalid transactionId", 400);
//        }

        $fromDate = $transaction->getCreatedAt();

        $this->contisSyncTransactionService->execute($account);

        $payProTransactions = $this->transactionRepository->getTransactionsOfAccountAfterDate($account, $fromDate);

        return $payProTransactions;
    }
}
