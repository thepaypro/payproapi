<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Exception\PayProException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\WalletInterface;

/**
 * Class Wallet
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Wallet implements WalletInterface
{
    protected $bitcoinWalletRequestService;
    protected $dockerComposePath;

    public function __construct(RequestService $bitcoinWalletRequestService, string $dockerComposePath)
    {
        $this->bitcoinWalletRequestService = $bitcoinWalletRequestService;
        $this->dockerComposePath = $dockerComposePath;
    }

    /**
     * Create a bitcoin wallet for an account
     * @param string $walletIdentification
     * @param string $tenant
     * @return bool true
     * @throws PayProException
     */
    public function create(string $walletIdentification, string $tenant): bool
    {
        $tenant = str_replace(' ', '', $tenant);
        $cmd = 'docker-compose -f '.$this->dockerComposePath.' run node ';
        $cmd = $cmd.'/var/www/bin/wallet create '.$tenant.'Wallet 1-1 '.$tenant.' -t -f /wallets/'.$walletIdentification.'.dat';

        $process = new Process($cmd);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new PayProException('ERROR BitcoinApiClient, error creating wallet: '.$e->getMessage(), 500);
        }

        $cmd = 'docker-compose -f '.$this->dockerComposePath.' run node ';
        $cmd = $cmd.'/var/www/bin/wallet address -f /wallets/'.$walletIdentification.'.dat';

        $process = new Process($cmd);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new PayProException('ERROR BitcoinApiClient, error creating address: '.$e->getMessage(), 500);
        }

        return true;
    }

    /**
     * Create a bitcoin wallet for an account
     * @param string $walletIdentification
     * @return array $response
     * @throws PayProException
     */
    public function getOne(string $walletIdentification): array
    {
        $cmd = 'docker-compose -f '.$this->dockerComposePath.' run node ';
        $cmd = $cmd.'/var/www/bin/wallet status -f /wallets/'.$walletIdentification.'.dat';

        $process = new Process($cmd);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new PayProException('ERROR BitcoinApiClient, error retreiving wallet balance: '.$e->getMessage(), 500);
        }

        $outputArray = explode("*" ,$process->getOutput());
        $balanceArray = explode(" ", end($outputArray));
        $balance = '';
        foreach ($balanceArray as $key => $element) {
            if ($element == 'bit') {
                $balance = $balanceArray[$key-1].' '.$element;
            }
        }

        $cmd = 'docker-compose -f '.$this->dockerComposePath.' run node ';
        $cmd = $cmd.'/var/www/bin/wallet addresses -f /wallets/'.$walletIdentification.'.dat';

        $process = new Process($cmd);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new PayProException('ERROR BitcoinApiClient, error retreiving wallet address: '.$e->getMessage(), 500);
        }


        $address = explode("\n", $process->getOutput())[1];
        $address = trim($address, " ");

        return [
            'balance' => $balance,
            'address' => $address
        ];
    }
}
