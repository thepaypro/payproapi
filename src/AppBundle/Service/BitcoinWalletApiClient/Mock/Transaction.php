<?php

namespace AppBundle\Service\BitcoinWalletApiClient\Mock;

use AppBundle\Service\BitcoinWalletApiClient\HashingService;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\TransactionInterface;

class Transaction implements TransactionInterface
{

    protected $hashingService;

    public function __construct(HashingService $hashingService)
    {
        $this->hashingService = $hashingService;
    }

    /**
     * Create a bitcoin Transaction from paypro account to elsewhere specified in
     * @param  array $transaction
     * @return array $response
     */
    public function create(array $transaction): array
    {
        $response = [
            'transactionId' => 'alphanumericHash',
            'amount' => "1",
            'subject' => 'transaction in bitcoins',
            'beneficiary' => 'alphanumericHashBeneficiaryAddress'
        ];


        return $response;
    }

    /**
     * List all the bitcoin Transactions for the wallet of given account
     * @param string $walletIdentification
     * @return array $response
     */
    public function getAll(string $walletIdentification): array
    {
        $transaction = [
            [
                'subject' => 'testSubject',
                'amount' => '1',
                'units' => 'bit',
                'direction' => 'received',
                'createdAt' => '2017-12-19 12:24:00'
            ]
        ];
        $transaction[0] = $this->hashingService->generateHashId($transaction[0]);

        // $response = [
        //     [
        //         'transactionId' => 1,
        //         'amount' => "1",
        //         'subject' => 'transaction in bitcoins',
        //         'beneficiary' => 'alphanumericHashBeneficiaryAddress'
        //     ],
        //     [
        //         'transactionId' => 2,
        //         'amount' => "10",
        //         'subject' => 'bigger transaction in bitcoins',
        //         'beneficiary' => 'alphanumericHashBeneficiaryAddress'
        //     ]
        // ];

        return $transaction;
    }
}
