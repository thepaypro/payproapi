<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Exception\PayProException;
use Symfony\Component\Process\Process;
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
        $cmd = 'docker-compose -f /var/bitcoinWalletCLI/docker-compose.yml run';
        $cmd = $cmd.'/var/www/bin/wallet create '.$tenant.'Wallet 1-1 /wallets/'.$walletIdentification.'.dat';

        $process = new Process($cmd);
        $process->run();

        dump($process->getOutput());die();
    }

    /**
     * Create a bitcoin wallet for an account
     * @param string $walletIdentification
     * @return array $response
     * @throws PayProException
     */
    public function getOne(string $walletIdentification) : array
    {
        try {
            $response = $this->bitcoinWalletRequestService->call(
                'GET',
                '/wallet',
                [
                    'filename' => $walletIdentification
                ]
            );
        } catch (Exception $exception) {
            throw new PayProException('Bitcoin Wallet service unavailable', 500);
        }

        return $response;
    }
}
