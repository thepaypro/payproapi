<?php

namespace AppBundle\Service\BitcoinTransaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\BitcoinWalletApiClient\Wallet;
use AppBundle\Service\BitcoinWalletApiClient\Transaction;

/**
 * Class CreateBitcoinTransactionService
 */
class CreateBitcoinTransactionService
{
    protected $userRepository;
    protected $bitcoinTransactionApiClient;
    protected $bitcoinWalletApiClient;

    public function __construct(
        Wallet $bitcoinWalletApiClient,
        Transaction $bitcoinTransactionApiClient
    )
    {
        $this->$bitcoinWalletApiClient = $bitcoinWalletApiClient;
        $this->$bitcoinTransactionApiClient = $bitcoinTransactionApiClient;
    }

    /**
     * @param int $userId
     * @param string $beneficiary
     * @param int $amount
     * @param string $subject
     * @return array
     * @throws PayProException
     */
    public function execute(
        int $userId,
        string $beneficiary,
        int $amount,
        string $subject
    ): array
    {
        if (!is_string($subject) || strlen($subject) > 100) {
            throw new PayProException('Subject must be a string shorter than 100 characters', 400);
        }

        $user = $this->userRepository->findOneById($userId);
        $payer = $user->getAccount();

        if (!$payer) {
            throw new PayProException('Account not found', 400);
        }

//        $wallet = $this->bitcoinWalletApiClient->get($payer);
//
//        if ($amount > $wallet['amount']) {
//            throw new PayProException('Insufficient funds', 400);
//        }

        $transaction = [
            'payer' => $payer->getId(),
            'beneficiaryWalletAddress' => $beneficiary,
            'amount' => $amount,
            'subject' => $subject
        ];

        $bitcoinTransaction = $this->bitcoinTransactionApiClient->create($transaction);

        return $bitcoinTransaction;
    }
}
