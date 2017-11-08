<?php

namespace AppBundle\Service\BitcoinTransaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\BitcoinTransactionRepository;
use AppBundle\Repository\UserRepository;


/**
 * Class LastTransactionsService
 */
class LastTransactionsService
{
    protected $bitcoinTransactionRepository;
    protected $userRepository;
    protected $bitcoinSyncTransactionService;

    /**
     * @param BitcoinTransactionRepository $bitcoinTransactionRepository
     * @param UserRepository $userRepository
     * @param BitcoinSyncTransactionService $bitcoinSyncTransactionService
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
     * This method retrieves all the transactions after a given transaction timestamp.
     *
     * @param  int $userId
     * @param int $transactionId
     * @return array $transactions
     * @throws PayProException
     * @internal param string $fromDate
     * @internal param string $toDate
     */
    public function execute(
        int $userId,
        int $transactionId = null
    ): array
    {
        $user = $this->userRepository->findOneById($userId);
        $account = $user->getBitcoinAccount();
        $accountIsBeneficiary = false;
        $accountIsPayer = false;

        $transaction = $this->bitcoinTransactionRepository->findOneById($transactionId);

        if (!$account) {
            throw new PayProException("invalid token", 400);
        }
        if (!$transaction || !($transaction->getPayer() || $transaction->getBeneficiary())) {
            throw new PayProException("invalid bitcoinTransactionId", 400);
        }
        if ($transaction->getPayer()) {
            $accountIsPayer = ($transaction->getPayer()->getId() == $account->getId());
        }
        if ($transaction->getBeneficiary()) {
            $accountIsBeneficiary = ($transaction->getBeneficiary()->getId() == $account->getId());
        }
        if (!$accountIsBeneficiary && !$accountIsPayer) {
            throw new PayProException("invalid bitcoinTransactionId", 400);
        }

        $this->bitcoinSyncTransactionService->execute($user);

        $payProTransactions = $this->bitcoinTransactionRepository->getTransactionsOfAccountAfterTransactionId(
            $account,
            $transaction->getId()
        );

        dump($payProTransactions);die();

        return $payProTransactions;
    }
}
