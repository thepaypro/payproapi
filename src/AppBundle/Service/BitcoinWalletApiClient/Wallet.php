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
    protected $testnetMode;
    protected $bitcoreWalletProcessService;

    public function __construct(
        bool $testnetMode,
        BitcoinWalletProcessService $bitcoreWalletProcessService)
    {
        $this->testnetMode = $testnetMode;
        $this->bitcoreWalletProcessService = $bitcoreWalletProcessService;
    }

    /**
     * Create a bitcoin wallet for an account
     * @param string $walletIdentification
     * @param string $tenant
     * @return bool true
     * @throws PayProException
     */
    public function create(string $walletIdentification, string $tenant): array
    {
        $tenant = str_replace(' ', '', $tenant);

        $testnet = ' ';

        if ($this->testnetMode) {
            $testnet = ' -t ';
        }

        try {
            $this->bitcoreWalletProcessService->process( ' create '.$tenant.'Wallet 1-1 '.$tenant.$testnet, $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('Creating Wallet: '.$e->getMessage(), 500);
        }

        try {
            $this->bitcoreWalletProcessService->process( ' address ', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('ERROR generating address: '.$e->getMessage(), 500);
        }

        try {
            $output = $this->bitcoreWalletProcessService->process( 'addresses', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('ERROR retrieving wallet address: '.$e->getMessage(), 500);
        }

        $address = explode("\n", $output)[1];
        $address = trim($address, " ");

        return ['address' => $address];
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
            $output = $this->bitcoreWalletProcessService->process( 'status', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('ERROR retrieving wallet balance: '.$e->getMessage(), 500);
        }
        // dump($output);die();
        $outputArray = explode("*" , $output);

        $balance = '';

        foreach ($outputArray as $key => $element) {
            $balanceArray = explode(" ", $outputArray[$key]);
            foreach ($balanceArray as $key => $element) {
                if ($element == 'Balance') {
                    $balance = $balanceArray[$key+1];
                }
            }
        }
        
        try {
            $output = $this->bitcoreWalletProcessService->process( 'addresses', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('ERROR retrieving wallet address: '.$e->getMessage(), 500);
        }

        $address = explode("\n", $output)[1];
        $address = trim($address, " ");

        return [
            'balance' => $this->stringToFloat($balance),
            'address' => $address,
            'units' => 'bit'
        ];
    }

    private function stringToFloat(string $num): float {
        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        }

        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
        );
    }
}
