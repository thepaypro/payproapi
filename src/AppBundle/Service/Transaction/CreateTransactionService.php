<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Service\Balance\GetBalanceService;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use AppBundle\Entity\Transaction;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Exception\PayProException;
use AppBundle\Service\ContisApiClient\Transaction as ContisTransactionApiClient;

/**
 * Class CreateTransactionService
 */
class CreateTransactionService
{
    protected $transactionRepository;
    protected $accountRepository;
    protected $userRepository;
    protected $validationService;
    protected $getBalanceService;
    protected $contisTransactionApiClient;

    /**
     * @param TransactionRepository $transactionRepository
     * @param AccountRepository $accountRepository
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validationService
     * @param GetBalanceService $getBalanceService
     * @param ContisTransactionApiClient $contisTransactionApiClient
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        AccountRepository $accountRepository,
        UserRepository $userRepository,
        ValidatorInterface $validationService,
        GetBalanceService $getBalanceService,
        ContisTransactionApiClient $contisTransactionApiClient
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
        $this->getBalanceService = $getBalanceService;
        $this->contisTransactionApiClient = $contisTransactionApiClient;
    }

    /**
     * This method will retrieve all the transactions from the database and from Contis and will merge them.
     *
     * @param  int $userId
     * @param  int $beneficiaryId
     * @param  float|int $amount
     * @param  string $subject
     * @return Transaction $transaction
     * @throws PayProException
     */
    public function execute(
        int $userId,
        int $beneficiaryId,
        float $amount,
        string $subject
    ) : Transaction
    {
        $user = $this->userRepository->findOneById($userId);
        $payer = $user->getAccount();
        $beneficiary = $this->accountRepository->findOneById($beneficiaryId);

        if (!$payer) {throw new PayProException('Account not found', 400);}
        if ($payer == $beneficiary) {throw new PayProException('Beneficary account and destination account can not be the same', 400);}
        if (!$beneficiary) {throw new PayProException('Beneficiary not found', 400);}
        if ($amount > $this->getBalanceService->execute($userId)) {
            throw new PayProException('Insufficient funds', 400);
        }

        $transaction = new Transaction(
            $payer,
            $beneficiary,
            $amount,
            $subject
        );

        $errors = $this->validationService->validate($transaction);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath().': '.$error->getMessage(), 404);
            }
        }

        $contisTransaction = $this->contisTransactionApiClient->create($transaction);

        $transaction->setContisTransactionId($contisTransaction['TransactionID']);

        $this->transactionRepository->save($transaction);

        return $transaction;
    }
}
