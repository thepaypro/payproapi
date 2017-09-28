<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Exception\PayProException;
use Exception;
use GuzzleHttp\Client;

/**
 * Class Wallet
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Wallet
{
    protected $httpClient;
    protected $bitcoinWalletRequestService;

    public function __construct(RequestService $bitcoinWalletRequestService) {
        $this->bitcoinWalletRequestService = $bitcoinWalletRequestService;
        $this->httpClient = new Client();
    }

    /**
     * Create a bitcoin wallet for an account
     * @param string $walletIdentification
     * @param string $tenant
     * @return array $response
     * @throws PayProException
     */
    public function create(string $walletIdentification, string $tenant) : bool
    {
        try {
            $this->bitcoinWalletRequestService->call(
                'POST',
                '/wallet',
                [
                    'filename' => $walletIdentification,
                    'tenant' => $tenant
                ]
            );
        } catch (Exception $exception) {
            throw new PayProException('Bitcoin Wallet service unavailable', 500);
        }

        return true;
    }

    /**
     * Create a bitcoin wallet for an account
     * @param string $walletIdentification
     * @param string $tenant
     * @return array $response
     * @throws PayProException
     */
    public function fakeCreate(string $walletIdentification, string $tenant) : bool
    {
        return true;
    }

    /**
     * Create a bitcoin wallet for an account
     * @param string $walletIdentification
     * @return array $response
     * @throws PayProException
     */
    public function get(string $walletIdentification, string $tenant) : array
    {
        try {
            $response = $this->bitcoinWalletRequestService->call(
                'get',
                '/wallet',
                [
                    'filename' => $walletIdentification,
                    'tenant' => $tenant
                ]
            );
        } catch (Exception $exception) {
            throw new PayProException('Bitcoin Wallet service unavailable', 500);
        }

        return $response;
    }
}
