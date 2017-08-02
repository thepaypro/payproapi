<?php

namespace AppBundle\Service\Transaction;

use DateTime;

use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Entity\Transaction;
use AppBundle\Exception\PayProException;
use AppBundle\Service\ContisApiClient\Transaction as ContisTransactionApiClient;

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
     * @param TransactionRepository      $transactionRepository
     * @param AccountRepository          $accountRepository
     * @param UserRepository             $userRepository
     * @param ContisTransactionApiClient $contisTransactionApiClient
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        AccountRepository $accountRepository,
        UserRepository $userRepository,
        ContisTransactionApiClient $contisTransactionApiClient
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->contisTransactionApiClient = $contisTransactionApiClient;
    }

    /**
     * This method will retrieve all the transactions from the database and from Contis and will merge them.
     *
     * @param  int    $userId
     * @param  int    $payerId
     * @param  int    $beneficiaryId
     * @param  string $fromDate
     * @param  string $toDate
     * @return array  $transactions
     */
    public function execute(
        int $userId,
        string $fromDate = null,
        string $toDate = null
    ) : array
    {
        $user = $this->userRepository->findOneById($userId);
        $account = $user->getAccount();

        if (!$fromDate) {
            $fromDate = $account->getCreatedAt();
        }

        if (!$toDate) {
            $toDate = new DateTime();
        }

        $contisTransactions = $this->contisTransactionApiClient->getAll($account, $fromDate, $toDate);
        foreach ($contisTransactions as $contisTransaction) {
            $transaction = $this->transactionRepository->findOneByContisTransactionId($contisTransaction['TransactionID']);
            if ($transaction && $transaction->getId() == 19) {
                dump($transaction->getId());
                dump($transaction->getCreatedAt());
                $time = intval(trim($contisTransaction['SettlementDate'], '/Date()')/1000);
                $creationDateTime = (new DateTime())->setTimestamp($time);
                dump($creationDateTime);
                die();
                continue;
            }

            $time = intval(trim($contisTransaction['SettlementDate'], '/Date()')/1000) - 2*60*60;
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

        $payProTransactions = $this->transactionRepository->getTransactionsOfAccount($account);

        return $payProTransactions;
    }
}
