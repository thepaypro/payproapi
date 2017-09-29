<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Exception\PayProException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Class ProcessService
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class BitcoreWalletProcessService
{
    protected $dockerComposeCommand;

    /**
     * @param string $dockerComposePath
     */
    public function __construct(string $dockerComposePath)
    {
        $this->dockerComposeCommand = 'docker-compose -f '.$dockerComposePath.' run node /var/www/bin/wallet ';
    }

    /**
     * @param string $bitcoreWalletCommand
     * @param int $walletIdentification
     * @return string
     * @throws PayProException
     */
    public function process(string $bitcoreWalletCommand, int $walletIdentification): string
    {
        $cmd = $this->dockerComposeCommand.$bitcoreWalletCommand;
        $cmd = $cmd.$this->walletFileSpecification($walletIdentification);

        $process = new Process($cmd);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new PayProException('ERROR BitcoinApiClient: '.$e->getMessage(), 500);
        }

        return $process->getOutput();
    }

    /**
     * @param int $walletIdentification
     * @return string
     */
    private function walletFileSpecification(int $walletIdentification) : string
    {
        return  ' -f /wallets/'.$walletIdentification.'.dat';
    }
}
