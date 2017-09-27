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
    protected $bitcoinWalletClientHost;

    public function __construct(string $bitcoinWalletClientHost) {
        $this->bitcoinWalletClientHost;
        $this->httpClient = new Client();
    }

    /**
     * Create a bitcoin wallet for an account
     * @param  AccountEntity $account
     * @return array $response
     */
    public function create(AccountEntity $account) : array
    {

        try {
            $response = $this->httpClient->request(
                'POST',
                $this->bitcoinWalletClientHost."/wallet",
                [
                    'headers' => [
                        'Content-type' => 'application/json'
                    ],
                    'connect_timeout' => 20,
                    'body' => $payload
                ]
            );

        } catch (Exception $e) {

        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
