<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Exception\PayProException;
use AppBundle\Service\BitcoinWalletApiClient\Interfaces\WalletInterface;


/**
 * Class Wallet
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Wallet implements WalletInterface
{
    protected $bitcoreWalletProcessService;

    public function __construct(BitcoreWalletProcessService $bitcoreWalletProcessService)
    {
        $this->bitcoreWalletProcessService = $bitcoreWalletProcessService;
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

        try {
            $this->bitcoreWalletProcessService->process( ' create '.$tenant.'Wallet 1-1 '.$tenant.' -t ', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('Creating Wallet: '.$e->getMessage(), 500);
        }

        try {
            $this->bitcoreWalletProcessService->process( ' address ', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('ERROR generating address: '.$e->getMessage(), 500);
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
        try {
            $output = $this->bitcoreWalletProcessService->process( ' status ', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('ERROR retrieving wallet balance: '.$e->getMessage(), 500);
        }

        $outputArray = explode("*" , $output);
        $balanceArray = explode(" ", end($outputArray));
        $balance = '';

        foreach ($balanceArray as $key => $element) {
            if ($element == 'bit') {
                $balance = $balanceArray[$key-1].' '.$element;
            }
        }

        try {
            $output = $this->bitcoreWalletProcessService->process( ' addresses ', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('ERROR retrieving wallet address: '.$e->getMessage(), 500);
        }

        $address = explode("\n", $output)[1];
        $address = trim($address, " ");

        return [
            'balance' => $balance,
            'address' => $address
        ];
    }
}
