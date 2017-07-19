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
     * @param  String $fromDate
     * @param  String $toDate
     * @return Array  $transactions
     */
    public function execute(
        int $userId,
        String $fromDate = null,
        String $toDate = null
    ) : Array
    {
        $user = $this->userRepository->findOneById($userId);
        $account = $user->getAccount();

        if (!$fromDate) {
            $fromDate = $account->getCreatedAt();
            $fromDate = DateTime::createFromFormat('m/d/Y', '5/7/2017');
        }

        if (!$toDate) {
            $toDate = new DateTime();
        }
        // $payProTransactions = $this->transactionRepository->findBy($queryParams);
        $contisTransactions = $this->contisTransactionApiClient->getAll($account, $fromDate, $toDate);

        return $contisTransactions;
    }
}
