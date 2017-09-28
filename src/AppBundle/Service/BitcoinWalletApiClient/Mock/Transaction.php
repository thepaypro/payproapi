<?php

namespace AppBundle\Service\BitcoinWalletApiClient\Mock;

use AppBundle\Service\BitcoinWalletApiClient\Interfaces\TransactionInterface;

class Transaction implements TransactionInterface
{

    /**
     * Create a bitcoin Transaction from paypro account to elsewhere specified in
     * @param  array $transaction
     * @return array $response
     */
    public function create(array $transaction): array
    {
        $response = [
            'transactionId' => 'alphanumericId',
            'amount' => "1",
            'subject' => 'transaction in bitcoins',
            'destinatary' => 'Other Jhon Doe'
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

        $response = [
            [
                'transactionId' => 'alphanumericId',
                'amount' => "1",
                'subject' => 'transaction in bitcoins',
                'destinatary' => 'Other Jhon Doe'
            ],
            [
                'transactionId' => 'alphanumericId2',
                'amount' => "10",
                'subject' => 'bigger transaction in bitcoins',
                'destinatary' => 'Other Jhon Doe'
            ]
        ];

        return $response;
    }
}
