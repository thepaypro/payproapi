<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Service\ContisApiClient\Transaction as ContisTransactionApiClient;
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
     *
     * @param Account $account
     * @return void
     */
    public function execute(
        Account $account)
    {
        $lastSyncedTransaction = $account->getLastSyncedTransaction();

        $fromDate = $account->getCreatedAt();
        $toDate = new DateTime();
        $lastSyncedTransactionFound = false;

        if ($lastSyncedTransaction == null) {
            $this->initiallyParseContisTransactions($account, $fromDate, $toDate);
        } else {
            while (!$lastSyncedTransactionFound) {

                $contisTransactions = $this->contisTransactionApiClient->getAll($account, $fromDate, $toDate);

                $lastSyncedTransactionFound = $this->parseContisTransactions($contisTransactions, $account, $lastSyncedTransaction);

                $time = intval(trim(end($contisTransactions)['SettlementDate'], '/Date()') / 1000) - 2 * 60 * 60;
                $toDate = (new DateTime())->setTimestamp($time);
            }
        }

        $transactions = $this->transactionRepository->getTransactionsOfAccount($account, 1, 1);

        if (!empty($transactions['content'])) {

            $newLastSyncedTransaction = $transactions['content'][0];
            $account->setLastSyncedTransaction($newLastSyncedTransaction);
            $this->accountRepository->save($account);
        }
    }

    /**
     * Method to be run when there are no synced transactions in the Account,
     * which parses all the transactions that contis has for the user.
     * @param Account $account
     * @param DateTime $fromDate
     * @param DateTime $toDate
     */
    private function initiallyParseContisTransactions(
        Account $account,
        DateTime $fromDate,
        DateTime $toDate)
    {
        do {
            $contisTransactions = $this->contisTransactionApiClient->getAll($account, $fromDate, $toDate);

            foreach ($contisTransactions as $contisTransaction) {
                $transaction = $this->transactionRepository->findOneByContisTransactionId($contisTransaction['TransactionID']);

                if ($transaction) {
                    continue;
                }

                $this->processTransaction($account, $contisTransaction);
            }

            $time = intval(trim(end($contisTransactions)['SettlementDate'], '/Date()') / 1000) - 2 * 60 * 60;
            $toDate = (new DateTime())->setTimestamp($time);
        } while (count($contisTransactions) == 50);

        return;
    }

    /**
     * Regular method for when the user has a last synced transaction, which parses
     * all the transactions until it finds the last synced one.
     * @param array $contisTransactions
     * @param Account $account
     * @param Transaction $lastSyncedTransaction
     * @return bool
     */
    private function parseContisTransactions(
        array $contisTransactions,
        Account $account,
        Transaction $lastSyncedTransaction
    ): bool
    {

        foreach ($contisTransactions as $contisTransaction) {
            $transaction = $this->transactionRepository->findOneByContisTransactionId($contisTransaction['TransactionID']);

            if ($lastSyncedTransaction->getContisTransactionId() == $contisTransaction['TransactionID']) {
                return true;
            }

            if ($transaction) {
                continue;
            }

            $this->processTransaction($account, $contisTransaction);
        }

        return false;
    }

    /**
     * Generic method that receives an account and a contis transaction and adds the
     * last one to the PayPro transactions if its missing from the list.
     * @param $account
     * @param $contisTransaction
     */
    private function processTransaction($account, $contisTransaction)
    {
        $time = intval(trim($contisTransaction['SettlementDate'], '/Date()') / 1000) - 2 * 60 * 60;
        $creationDateTime = (new DateTime())->setTimestamp($time);

        $transaction = new Transaction(
            null,
            null,
            $contisTransaction['SettlementAmount'],
            $contisTransaction['Description'],
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
