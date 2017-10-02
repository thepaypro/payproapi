<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Service\BitcoinWalletApiClient\Interfaces\TransactionInterface;

/**
 * Class Transaction
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Transaction implements TransactionInterface
{
    protected $bitcoinWalletRequestService;

    public function __construct(RequestService $bitcoinWalletRequestService)
    {
        $this->bitcoinWalletRequestService = $bitcoinWalletRequestService;
    }

    /**
     * Create a bitcoin Transaction from paypro account to elsewhere specified in
     * @param  array $transaction
     * @return array $response
     */
    public function create(array $transaction): array
    {
        return true;
    }

    /**
     * List all the bitcoin Transactions for the wallet of given account
     * @param string $walletIdentification
     * @return array $response
     */
    public function getAll(string $walletIdentification): array
    {
        return true;
    }
}
