<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\ContisApiClient\Transaction as ContisTransactionApiClient;
use DateTime;

/**
 * Class IndexTransactionService
 */
class IndexTransactionService
{
    protected $transactionRepository;
    protected $accountRepository;
    protected $userRepository;
    protected $contisTransactionApiClient;

    /**
     * @param TransactionRepository $transactionRepository
     * @param AccountRepository $accountRepository
     * @param UserRepository $userRepository
     * @param ContisTransactionApiClient $contisTransactionApiClient
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        AccountRepository $accountRepository,
        UserRepository $userRepository,
        ContisTransactionApiClient $contisTransactionApiClient
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->contisTransactionApiClient = $contisTransactionApiClient;
    }

    /**
     * This method will retrieve all the transactions from the database and from Contis and will merge them.
     *
     * @param  int $userId
     * @param  string $fromDate
     * @param  string $toDate
     * @param int $page
     * @param int $size
     * @return array $transactions
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

        if (!$fromDate) {
            $fromDate = $account->getCreatedAt();
        }

        if (!$toDate) {
            $toDate = new DateTime();
        }

//        //TODO: Decide what to do to sync our Transactions with the contis ones.
//        $contisTransactions = $this->contisTransactionApiClient->getAll($account, $fromDate, $toDate);
//
//        foreach ($contisTransactions as $contisTransaction) {
//            $transaction = $this->transactionRepository->findOneByContisTransactionId($contisTransaction['TransactionID']);
//            if ($transaction) {
//                continue;
//            }
//
//            $time = intval(trim($contisTransaction['SettlementDate'], '/Date()') / 1000) - 2 * 60 * 60;
//            $creationDateTime = (new DateTime())->setTimestamp($time);
//
//            $transaction = new Transaction(
//                null,
//                null,
//                $contisTransaction['SettlementAmount'],
//                $contisTransaction['Description'],
//                $creationDateTime
//            );
//            $transaction->setContisTransactionId($contisTransaction['TransactionID']);
//
//            if ($account->getAccountNumber() == $contisTransaction['TranFromAccountNumber']) {
//                $transaction->setPayer($account);
//            }
//            if ($account->getAccountNumber() == $contisTransaction['TranToAccountNumber']) {
//                $transaction->setBeneficiary($account);
//            }
//            $this->transactionRepository->save($transaction);
//        }

        $payProTransactions = $this->transactionRepository->getTransactionsOfAccount($account, $page, $size);

        return $payProTransactions;
    }
}
