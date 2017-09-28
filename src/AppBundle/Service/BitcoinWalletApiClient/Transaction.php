<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Service\BitcoinWalletApiClient\Interfaces\TransactionInterface;
use GuzzleHttp\Client;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class Transaction
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class Transaction implements TransactionInterface
{
    protected $httpClient;
    protected $bitcoinWalletRequestService;

    public function __construct(RequestService $bitcoinWalletRequestService)
    {
        $this->bitcoinWalletRequestService = $bitcoinWalletRequestService;
        $this->httpClient = new Client();
    }

    /**
     * Create a bitcoin Transaction from paypro account to elsewhere specified in
     * @param  array $transaction
     * @return array $response
     */
    public function create(array $transaction): array
    {
        $response = $this->bitcoinWalletRequestService->call(
            'POST',
            '/transaction',
            [
                'filename' => $transaction['payer'],
                'beneficiaryWalletAddress' => $transaction['beneficiaryWalletAddress'],
                'amount' => $transaction['amount'],
                'subject' => $transaction['subject']
            ]
        );

        return $response;
    }

    /**
     * List all the bitcoin Transactions for the wallet of given account
     * @param string $walletIdentification
     * @return array $response
     */
    public function getAll(string $walletIdentification): array
    {
        try {
            $response = $this->bitcoinWalletRequestService->call(
                'GET',
                '/transaction',
                [
                    'filename' => $walletIdentification
                ]
            );
        } catch (Exception $exception) {
            throw PayProException('BitcoreWallet, service unavailable', 500);
        }

        return $response;
    }
}
