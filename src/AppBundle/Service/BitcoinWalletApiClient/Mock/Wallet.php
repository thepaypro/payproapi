<?php

namespace AppBundle\Service\BitcoinWalletApiClient\Mock;

use AppBundle\Service\BitcoinWalletApiClient\Interfaces\WalletInterface;

/**
 * Class Wallet
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Wallet implements walletInterface
{
    /**
     * Create a bitcoin wallet for an account
     * @param string $walletIdentification
     * @param string $tenant
     * @return array $response
     * @throws PayProException
     */
    public function create(string $walletIdentification, string $tenant): bool
    {
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
        return ['balance' => '100.00', 'address' => 'mutHtH6DbgJiKa9dVEmNhrBiNJUsDuYpdb', 'units' => 'bit'];
    }
}
