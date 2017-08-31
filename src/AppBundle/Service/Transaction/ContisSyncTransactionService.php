<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Service\ContisApiClient\Transaction as ContisTransactionApiClient;
use DateInterval;
use DateTime;

/**
 * Class CreateTransactionService
 */
class ContisSyncTransactionService
{
    protected $transactionRepository;
    protected $accountRepository;
    protected $contisTransactionApiClient;

    /**
     * @param TransactionRepository $transactionRepository
     * @param AccountRepository $accountRepository
     * @param ContisTransactionApiClient $contisTransactionApiClient
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        AccountRepository $accountRepository,
        ContisTransactionApiClient $contisTransactionApiClient
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->contisTransactionApiClient = $contisTransactionApiClient;
    }

    /**
     * This method will retrieve all the transactions from the database and from Contis and will merge them.
     * @param Account $account
     * @return void
     */
    public function execute(
        Account $account)
    {
        $lastSyncedTransaction = $account->getLastSyncedTransaction();

        $fromDate = $account->getCreatedAt();
        $toDate = new DateTime();
        $toDate->add(new DateInterval('PT2H'));

        $this->persistContisTransactionsUntilLastSyncedTransactionIsFound(
            $account,
            $fromDate,
            $toDate,
            $lastSyncedTransaction
        );

        $transactions = $this->transactionRepository->getTransactionsOfAccount($account, 1, 1);

        if (!empty($transactions['content'])) {
            $newLastSyncedTransaction = $transactions['content'][0];
            $account->setLastSyncedTransaction($newLastSyncedTransaction);
            $this->accountRepository->save($account);
        }
    }

    /**
     * Get all transaction from Contis on a date interval and persist them
     * until the last synced transaction is found (if there is last synced transaction).
     * @param Account $account
     * @param DateTime $fromDate
     * @param DateTime $toDate
     * @param Transaction $lastSyncedTransaction
     */
    private function persistContisTransactionsUntilLastSyncedTransactionIsFound(
        Account $account,
        DateTime $fromDate,
        DateTime $toDate,
        Transaction $lastSyncedTransaction = null)
    {
        do {
            $contisTransactions = $this->contisTransactionApiClient->getAll($account, $fromDate, $toDate);

            $lastSyncedTransactionFound = $this->persistContisTransactions(
                $account,
                $contisTransactions,
                $lastSyncedTransaction
            );

            $time = intval(trim(end($contisTransactions)['SettlementDate'], '/Date()') / 1000);
            $toDate = (new DateTime())->setTimestamp($time);
        } while (!$lastSyncedTransactionFound && count($contisTransactions) == 50);
    }

    /**
     * Regular method for when the user has a last synced transaction, which parses
     * all the transactions until it finds the last synced one.
     * @param Account $account
     * @param array $contisTransactions
     * @param Transaction $lastSyncedTransaction
     * @return bool
     */
    private function persistContisTransactions(
        Account $account,
        array $contisTransactions,
        Transaction $lastSyncedTransaction = null
    ): bool
    {
        foreach ($contisTransactions as $contisTransaction) {
            if (!is_null($lastSyncedTransaction) &&
                $lastSyncedTransaction->getContisTransactionId() == $contisTransaction['TransactionID']
            ) {
                return true;
            }

            $transaction = $this->transactionRepository->findOneByContisTransactionId(
                $contisTransaction['TransactionID']
            );

            if ($transaction) {
                continue;
            }

            $this->persistTransaction($account, $contisTransaction);
        }

        return false;
    }

    /**
     * Generic method that receives an account and a Contis transaction and adds the
     * last one to the PayPro transactions if its missing from the list.
     * @param Account $account
     * @param array $contisTransaction
     */
    private function persistTransaction(Account $account, array $contisTransaction)
    {
        /**
         * Remove 2 hours from the Contis date due to the difference of timezone
         * between their server and ours.
         */
        $time = intval(trim($contisTransaction['SettlementDate'], '/Date()') / 1000) - 2 * 60 * 60;
        $creationDateTime = (new DateTime())->setTimestamp($time);

        $transaction = new Transaction(
            null,
            null,
            $contisTransaction['SettlementAmount'],
            $contisTransaction['Description'],
            null,
            $creationDateTime
        );
        $transaction->setContisTransactionId($contisTransaction['TransactionID']);

        if ($account->getAccountNumber() == $contisTransaction['TranFromAccountNumber']) {
            $transaction->setPayer($account);
        }
        if ($account->getAccountNumber() == $contisTransaction['TranToAccountNumber']) {
            $transaction->setBeneficiary($account);
        }

        $this->transactionRepository->save($transaction);
    }
}
