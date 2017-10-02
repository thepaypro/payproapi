<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Service\BitcoinWalletApiClient\Interfaces\TransactionInterface;

/**
 * Class Transaction
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Transaction implements TransactionInterface
{
    protected $bitcoinWalletProcessService;

    public function __construct(BitcoinWalletProcessService $bitcoinWalletProcessService)
    {
        $this->bitcoinWalletProcessService = $bitcoinWalletProcessService;
    }

    /**
     * Create a bitcoin Transaction from paypro account to elsewhere specified in
     * @param  array $transaction
     * @return array $response
     */
    public function create(array $transaction): array
    {
        $cmd = 'send '.$transaction['beneficiaryWalletAddress'].' '.$transaction['amount'].'bit '.$transaction['subject'];

        try {
            $this->bitcoinWalletProcessService->process($cmd, $transaction['payer']);
        } catch (PayProException $e) {
            throw new PayProException('ERROR creating the transaction: '.$e->getMessage(), 500);
        }

        try {
            $this->bitcoinWalletProcessService->process('sign', $transaction['payer']);
        } catch (PayProException $e) {
            throw new PayProException('ERROR signing the transaction: '.$e->getMessage(), 500);
        }

        try {
            $output = $this->bitcoinWalletProcessService->process('broadcast', $transaction['payer']);
        } catch (PayProException $e) {
            throw new PayProException('ERROR broadcasting the transaction: '.$e->getMessage(), 500);
        }

        $output = explode(':', $output);
        $output = trim(end($output), " \n");

        return [
            'transactionId' => $output,
            'amount' => $transaction['amount'],
            'subject' => $transaction['subject'],
            'beneficiary' => $transaction['beneficiaryWalletAddress']
        ];
    }

    /**
     * List all the bitcoin Transactions for the wallet of given account
     * @param string $walletIdentification
     * @return array $response
     */
    public function getAll(string $walletIdentification): array
    {
        try {
            $this->bitcoinWalletProcessService->process('history', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('ERROR creating the transaction: '.$e->getMessage(), 500);
        }
    }
}
