<?php

namespace AppBundle\Service\ContisApiClient;

use AppBundle\Entity\Account as AccountEntity;
use GuzzleHttp\Client;

/**
 * Class Wallet
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Wallet
{
    protected $httpClient;
    protected $bitcoinWalletRequestService;

    public function __construct(string $bitcoinWalletRequestService) {
        $this->bitcoinWalletRequestService;
        $this->httpClient = new Client();
    }

    /**
     * Create a bitcoin wallet for an account
     * @param  AccountEntity $account
     * @return array $response
     */
    public function create(AccountEntity $account) : array
    {
        $response = $this->bitcoinWalletRequestService(
            'POST',
            '/wallet',
            ['filename' =>$account->getId()]
        );

        return $response;
    }
}
