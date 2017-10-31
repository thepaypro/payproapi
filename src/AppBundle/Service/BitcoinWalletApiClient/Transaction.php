<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Service\BitcoinWalletApiClient\Interfaces\TransactionInterface;
use DateTime;
/**
 * Class Transaction
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Transaction implements TransactionInterface
{
    protected $bitcoinWalletProcessService;
    protected $hashingService;

    public function __construct(BitcoinWalletProcessService $bitcoinWalletProcessService, HashingService $hashingService)
    {
        $this->bitcoinWalletProcessService = $bitcoinWalletProcessService;
        $this->hashingService = $hashingService;
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
            'beneficiary' => $transaction['beneficiaryWalletAddress'],
            'units' => 'bit'
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
            $output = $this->bitcoinWalletProcessService->process('history', $walletIdentification);
        } catch (PayProException $e) {
            throw new PayProException('ERROR creating the transaction: '.$e->getMessage(), 500);
        }
        // dump($output);die();
        $output = explode("\t", $output);
        array_splice($output, 0, 1);

        $transactions = [];
        foreach ($output as $key => $transaction) {
            // dump($transaction);die();
            $transactions[$key]['subject'] = $this->extractSubject($transaction);
            $transactions[$key]['amount'] = $this->extractAmount($transaction);
            $transactions[$key]['units'] = 'bit';
            $transactions[$key]['direction'] = $this->extractDirection($transaction);
            $transactions[$key]['createdAt'] = $this->extractTime($transaction);
            $transactions[$key] = $this->hashingService->generateHashId($transactions[$key]);
        }



        return $transactions;
    }

    private function extractSubject(string $transactionLine) {
        if ($transactionLine == explode("[", $transactionLine)[0]) {
            return "";
        }

        $transactionLine = explode("[", $transactionLine);
        $transactionLine = explode('"', $transactionLine[1]);
        return $transactionLine[1];
    }

    private function extractAmount(string $transactionLine) {
        $transactionLine = explode( ' ', $transactionLine);
        foreach ($transactionLine as $key => $part) {
            if ($part == 'bit') {
                return $transactionLine[$key-1];
            }
        }
    }

    private function extractDirection(string $transactionLine) {
        $transactionLine = explode( ' ', $transactionLine);
        return $transactionLine[count($transactionLine) - 6];
    }

    private function extractTime(string $transactionLine) {
        $transactionLine = explode( ' ', $transactionLine);
        $lastInTransactionLine = array_pop($transactionLine);
        $seconds = explode("\n", $lastInTransactionLine)[0];
        // $date = (new DateTime())->setTimestamp($seconds);
        // dump($date);die();
        // return $date->format("Y/m/d H:i:s");
        return $seconds;
    }
}
