<?php

namespace AppBundle\Service\BitcoinTransaction;

use AppBundle\Repository\UserRepository;
use AppBundle\Entity\BitcoinAccount;
use AppBundle\Entity\BitcoinTransaction;
use AppBundle\Repository\BitcoinAccountRepository;
use AppBundle\Repository\BitcoinTransactionRepository;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\TransactionInterface;
use DateInterval;
use DateTime;

/**
 * Class BitcoinSyncTransactionService
 */
class BitcoinSyncTransactionService
{
    protected $bitcoinTransactionRepository;
    protected $bitcoinAccountRepository;
    protected $bitcoinTransactionApiClient;

    /**
     * @param BitcoinTransactionRepository $bitcoinTransactionRepository
     * @param BitcoinAccountRepository $bitcoinAccountRepository
     * @param BitcoinTransactionApiClient $bitcoinTransactionApiClient
     */
    public function __construct(
        BitcoinTransactionRepository $bitcoinTransactionRepository,
        BitcoinAccountRepository $bitcoinAccountRepository,
        BitcoinTransactionApiClient $bitcoinTransactionApiClient
    )
    {
        $this->bitcoinTransactionRepository = $bitcoinTransactionRepository;
        $this->bitcoinAccountRepository = $bitcoinAccountRepository;
        $this->bitcoinTransactionApiClient = $bitcoinTransactionApiClient;
    }

    /**
     * This method will retrieve all the transactions from the database and from Blockchain and will merge them.
     * @param BitcoinAccount $bitcoinAccount
     * @return void
     */
    public function execute(
        BitcoinAccount $bitcoinAccount)
    {
        $lastSyncedTransaction = $user->getBitcoinAccount()->getLastSyncedTransaction();

        $this->persistBitcoinTransactionsUntilLastSyncedTransactionIsFound(
            $bitcoinAccount,
            $lastSyncedTransaction
        );

        $transactions = $this->bitcoinTransactionRepository->getTransactionsOfAccount($bitcoinAccount, 1, 1);

        if (!empty($transactions['content'])) {
            $newLastSyncedTransaction = $transactions['content'][0];
            $bitcoinAccount->setLastSyncedTransaction($newLastSyncedTransaction);
            $this->bitcoinAccountRepository->save($bitcoinAccount);
        }
    }

    /**
     * Get all transaction from Blockchain on a date interval and persist them
     * until the last synced transaction is found (if there is last synced transaction).
     * @param BitcoinAccount $bitcoinaccount
     * @param BitcoinTransaction $lastSyncedTransaction
     */
    private function persistBitcoinTransactionsUntilLastSyncedTransactionIsFound(
        BitcoinAccount $bitcoinAccount,
        BitcoinTransaction $lastSyncedTransaction = null)
    {
        do {
            $bitcoinTransactions = $this->bitcoinTransactionApiClient->getAll($user->getAccount()->getId());

            $lastSyncedTransactionFound = $this->persistBitcoinTransactions(
                $bitcoinAccount,
                $bitcoinTransactions,
                $lastSyncedTransaction
            );
        };
    }

    /**
     * Regular method for when the user has a last synced transaction, which parses
     * all the transactions until it finds the last synced one.
     * @param Account $account
     * @param array $contisTransactions
     * @param Transaction $lastSyncedTransaction
     * @return bool
     */
    private function persistBitcoinTransactions(
        BitcoinAccount $bitcoinAccount,
        array $blockchainTransactions,
        BitcoinTransaction $lastSyncedTransaction = null
    ): bool
    {
        foreach ($blockchainTransactions as $blockchainTransaction) {
            if (!is_null($lastSyncedTransaction) &&
                $lastSyncedTransaction->getBitcoinTransactionId() == $blockchainTransaction['TransactionID']
            ) {
                return true;
            }

            $transaction = $this->bitcoinTransactionRepository->findOneByBitcoinTransactionId(
                $blockchainTransaction['HashId']
            );

            if ($transaction) {
                continue;
            }

            $this->persistTransaction($bitcoinAccount, $blockchainTransaction);
        }

        return false;
    }

    /**
     * Generic method that receives an bitcoinAccount and a Blockchain transaction and adds the
     * last one to the PayPro bitcoinTransactions if its missing from the list.
     * @param BitcoinAccount $bitcoinaccount
     * @param array $bitcoinTransaction
     */
    private function persistTransaction(
        BitcoinAccount $bitcoinAccount,
        array $blockchainTransaction)
    {
        $time = intval(trim($blockchainTransaction['createdAt'], '/Date()') / 1000);
        $creationDateTime = (new DateTime())->setTimestamp($time);

        $bitcoinTransaction = new BitcoinTransaction(
            null,
            null,
            $contisTransaction['amount'],
            $contisTransaction['subject'],
            null,
            $creationDateTime
        );
        $bitcoinTransaction->setBlockchainTransactionId($blockchainTransaction['HashId']);

        if ($blockchainTransaction['direction'] == "sent") {
            $transaction->setPayer($bitcoinAccount);
        }
        else if ($contisTransaction['direction'] == "received") {
            $transaction->setBeneficiary($bitcoinAccount);
        }
        else if ($contisTransaction['direction'] == "moved") {
            $transaction->setPayer($bitcoinAccount);
            $transaction->setBeneficiary($bitcoinAccount);
        }

        $this->bitcoinTransactionRepository->save($transaction);
    }
}
