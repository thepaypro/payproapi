<?php

namespace AppBundle\Service\BitcoinWalletApiClient\Interfaces;

/**
 * Class Wallet
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
interface WalletInterface
{
    public function create(string $walletIdentification, string $tenant): bool;

    public function getOne(string $walletIdentification): array;
}
