<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Balance\GetBalanceService;
use AppBundle\Service\ContisApiClient\Transaction as ContisTransactionApiClient;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @internal param int $userId
     * @internal param int $beneficiaryId
     * @internal param float|int $amount
     * @internal param string $subject
     */
    public function execute(
        Account $account)
    {
        $lastSyncedTransaction = $account->getLastSyncedTransaction();

        $fromDate = $account->getCreatedAt();
        $toDate = new DateTime();
        $lastSyncedTransactionFound = false;

        while (!$lastSyncedTransactionFound) {

            $contisTransactions = $this->contisTransactionApiClient->getAll($account, $fromDate, $toDate);

            $lastSyncedTransactionFound = $this->parseContisTransactions($contisTransactions, $account, $lastSyncedTransaction);

            $toDate = end($contisTransactions)['SettlementDate'];
        }

        $transactions = $this->transactionRepository->getTransactionsOfAccount($account, 1, 1);
        $newLastSyncedTransaction = $transactions->content[0];

        $account->setLastSyncedTransaction($newLastSyncedTransaction);
        $this->accountRepository->save($account);
    }

    private function parseContisTransactions(
        array $contisTransactions,
        Account $account,
        Transaction $lastSyncedTransaction = null
    ) : bool
    {

        foreach ($contisTransactions as $contisTransaction) {
            $transaction = $this->transactionRepository->findOneByContisTransactionId($contisTransaction['TransactionID']);

            if ($lastSyncedTransaction->getContisTransactionId() == $contisTransaction['TransactionID']) {
                return true;
            }

            if ($transaction) {
                continue;
            }

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

        return false;
    }
}
