<?php

namespace AppBundle\Service\BitcoinTransaction;

use AppBundle\Entity\User;
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
     * @param TransactionInterface $bitcoinTransactionApiClient
     */
    public function __construct(
        BitcoinTransactionRepository $bitcoinTransactionRepository,
        BitcoinAccountRepository $bitcoinAccountRepository,
        TransactionInterface $bitcoinTransactionApiClient
    )
    {
        $this->bitcoinTransactionRepository = $bitcoinTransactionRepository;
        $this->bitcoinAccountRepository = $bitcoinAccountRepository;
        $this->bitcoinTransactionApiClient = $bitcoinTransactionApiClient;
    }

    /**
     * This method will retrieve all the transactions from the database and from Blockchain and will merge them.
     * @param User $user
     * @return void
     */
    public function execute(
        User $user)
    {
        $bitcoinAccount = $user->getBitcoinAccount();
        $lastSyncedTransaction = $bitcoinAccount->getLastSyncedTransaction();
        // dump($lastSyncedTransaction);die();
        $this->persistBitcoinTransactionsUntilLastSyncedTransactionIsFound(
            $user,
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
     * @param User $user
     * @param BitcoinTransaction $lastSyncedTransaction
     */
    private function persistBitcoinTransactionsUntilLastSyncedTransactionIsFound(
        User $user,
        BitcoinTransaction $lastSyncedTransaction = null)
    {
        $bitcoinTransactions = $this->bitcoinTransactionApiClient->getAll($user->getBitcoinAccount()->getId());

        $lastSyncedTransactionFound = $this->persistBitcoinTransactions(
            $user->getBitcoinAccount(),
            $bitcoinTransactions,
            $lastSyncedTransaction
        ); 
    }

    /**
     * Regular method for when the user has a last synced transaction, which parses
     * all the transactions until it finds the last synced one.
     * @param BitcoinAccount $bitcoinAccount
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
            // dump($blockchainTransaction);die();
            if (!is_null($lastSyncedTransaction) &&
                $lastSyncedTransaction->getBlockchainTransactionId() == $blockchainTransaction['HashId']
            ) {
                return true;
            }
            $transaction = $this->bitcoinTransactionRepository->findOneByBlockchainTransactionId(
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
        // $time = intval(trim($blockchainTransaction['createdAt'], '/Date()') / 1000);
        $creationDateTime = (new DateTime())->setTimestamp($blockchainTransaction['createdAt']);
        // dump($time)
        // dump($creationDateTime);die();
        // dump($blockchainTransaction['amount']*1000000);die();
        // dump((float) $blockchainTransaction['amount']);die();
        $bitcoinTransaction = new BitcoinTransaction(
            null,
            null,
            $blockchainTransaction['amount']*1000000,
            $blockchainTransaction['subject'],
            null,
            $creationDateTime
        );
        $bitcoinTransaction->setBlockchainTransactionId($blockchainTransaction['HashId']);

        if ($blockchainTransaction['direction'] == "sent") {
            $bitcoinTransaction->setPayer($bitcoinAccount);
        }
        else if ($blockchainTransaction['direction'] == "received") {
            $bitcoinTransaction->setBeneficiary($bitcoinAccount);
        }
        else if ($blockchainTransaction['direction'] == "moved") {
            $bitcoinTransaction->setPayer($bitcoinAccount);
            $bitcoinTransaction->setBeneficiary($bitcoinAccount);
        }

        $this->bitcoinTransactionRepository->save($bitcoinTransaction);
    }
}
