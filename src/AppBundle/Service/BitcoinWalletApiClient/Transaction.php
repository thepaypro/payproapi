<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Entity\Account as AccountEntity;
use GuzzleHttp\Client;

/**
 * Class Transaction
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Transaction
{
    protected $httpClient;
    protected $bitcoinWalletRequestService;

    public function __construct(string $bitcoinWalletRequestService)
    {
        $this->bitcoinWalletRequestService = $bitcoinWalletRequestService;
        $this->httpClient = new Client();
    }

    /**
     * Create a bitcoin Transaction from paypro account to elsewhere specified in
     * @param  array $transaction
     * @return array $response
     */
    public function create(array $transaction): array
    {

        $response = $this->bitcoinWalletRequestService(
            'POST',
            '/transaction',
            [
                'filename' => $transaction['payer'],
                'beneficiaryWalletAddress' => $transaction['beneficiaryWalletAddress'],
                'amount' => $transaction['amount'],
                'subject' => $transaction['subject']
            ]
        );

        return $response;
    }
}
