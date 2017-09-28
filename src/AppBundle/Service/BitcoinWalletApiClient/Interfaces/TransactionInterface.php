<?php

namespace AppBundle\Service\BitcoinWalletApiClient\Interfaces;

Interface TransactionInterface
{
    public function create(array $transaction): array;

    public function getAll(string $walletIdentification): array;
}