<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\TransactionRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Balance\GetBalanceService;
use AppBundle\Service\ContisApiClient\Transaction as ContisTransactionApiClient;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @internal param GetBalanceService $getBalanceService
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        AccountRepository $accountRepository,
        UserRepository $userRepository,
        ValidatorInterface $validationService,
        GetBalanceService $getBalanceService,
        ContisTransactionApiClient $contisTransactionApiClient
    )
    {
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
     * @param float|int $amount
     * @param  string $subject
     * @param string $title
     * @return Transaction $transaction
     * @throws PayProException
     */
    public function execute(
        int $userId,
        int $beneficiaryId,
        int $amount,
        string $subject,
        string $title
    ): Transaction
    {
        // First we do the validations that don't require a database query
        if (!is_string($subject) || strlen($subject) > 100) {
            throw new PayProException('Subject must be a string shorter than 100 characters', 400);
        }

        if (is_string($title) && strlen($title) > 255) {
            throw new PayProException('Title must be a string shorter than 255 characters', 400);
        }

        // We get the payer details in order to make the associated validations.
        $user = $this->userRepository->findOneById($userId);
        $payer = $user->getAccount();

        if (!$payer) {
            throw new PayProException('Account not found', 400);
        }
        if ($amount > $this->getBalanceService->execute($userId)) {
            throw new PayProException('Insufficient funds', 400);
        }

        // Finally we do the last required query and its validations.
        $beneficiary = $this->accountRepository->findOneById($beneficiaryId);

        if ($payer == $beneficiary) {
            throw new PayProException('Beneficary account and destination account can not be the same', 400);
        }
        if (!$beneficiary) {
            throw new PayProException('Beneficiary not found', 400);
        }

        $transaction = new Transaction(
            $payer,
            $beneficiary,
            $amount,
            $subject,
            $title
        );

        $errors = $this->validationService->validate($transaction);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath() . ': ' . $error->getMessage(), 404);
            }
        }

        $contisTransaction = $this->contisTransactionApiClient->create($transaction);

        $transaction->setContisTransactionId($contisTransaction['TransactionID']);

        $this->transactionRepository->save($transaction);

        return $transaction;
    }
}
