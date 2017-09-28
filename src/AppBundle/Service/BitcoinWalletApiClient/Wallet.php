<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Exception\PayProException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\WalletInterface;
use Exception;
use GuzzleHttp\Client;

/**
 * Class Wallet
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Wallet implements WalletInterface
{
    protected $httpClient;
    protected $bitcoinWalletRequestService;

    public function __construct(RequestService $bitcoinWalletRequestService)
    {
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
    public function create(string $walletIdentification, string $tenant): bool
    {
        $tenant = str_replace(' ', '', $tenant);
        $cmd = 'docker-compose -f /var/bitcoinWalletCLI/docker-compose.yml run node ';
        $cmd = $cmd.'/var/www/bin/wallet create '.$tenant.'Wallet 1-1 '.$tenant.' -t -f /wallets/'.$walletIdentification.'.dat';

        $process = new Process('docker-compose -f /var/bitcoinWalletCLI/docker-compose.yml run node /var/www/bin/wallet create TESTKWallet 1-1 TESTK -t -f /wallets/testk.dat');

        try {
            $process->mustRun();

            dump($process->getOutput());die();
        } catch (ProcessFailedException $e) {
            dump($e->getMessage());die();
        }
    }

    /**
     * Create a bitcoin wallet for an account
     * @param string $walletIdentification
     * @return array $response
     * @throws PayProException
     */
    public function getOne(string $walletIdentification): array
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
