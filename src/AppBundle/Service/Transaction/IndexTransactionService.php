<?php

namespace AppBundle\Service\Transaction;

use DateTime;

use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\UserRepository;
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
    protected $validationService;
    protected $contisTransactionApiClient;

    /**
     * @param TransactionRepository      $transactionRepository
     * @param ValidatorInterface         $validationService
     * @param ContisTransactionApiClient $contisAccountApiClient
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
        // $payProTransactions = $this->transactionRepository->findBy($queryParams);
        $contisTransactions = $this->contisTransactionApiClient->getAll($account, $fromDate, $toDate);

        return $contisTransactions;
    }
}
