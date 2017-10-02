<?php

namespace AppBundle\Service\BitcoinTransaction;

use AppBundle\Exception\PayProException;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\WalletInterface;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\TransactionInterface;

/**
 * Class CreateBitcoinTransactionService
 */
class CreateBitcoinTransactionService
{
    protected $userRepository;
    protected $bitcoinWalletApiClient;
    protected $bitcoinTransactionApiClient;

    public function __construct(
        UserRepository $userRepository,
        TransactionInterface $bitcoinTransactionApiClient,
        WalletInterface $bitcoinWalletApiClient
    )
    {
        $this->userRepository = $userRepository;
        $this->bitcoinWalletApiClient = $bitcoinWalletApiClient;
        $this->bitcoinTransactionApiClient = $bitcoinTransactionApiClient;
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

//        if (!$payer) {
//            throw new PayProException('Account not found', 400);
//        }

//        $wallet = $this->bitcoinWalletApiClient->getOne($payer->getId());
        $wallet = $this->bitcoinWalletApiClient->getOne('testnet');

        if ($amount > $wallet['balance']) {
            throw new PayProException('Insufficient funds', 400);
        }

        $transaction = [
//            'payer' => $payer->getId(),
            'payer' => 'testnet',
            'beneficiaryWalletAddress' => $beneficiary,
            'amount' => $amount,
            'subject' => $subject
        ];

        $bitcoinTransaction = $this->bitcoinTransactionApiClient->create($transaction);

        return $bitcoinTransaction;
    }
}
