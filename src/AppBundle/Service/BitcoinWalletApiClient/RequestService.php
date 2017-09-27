<?php

namespace AppBundle\Service\BitcoinWalletApiClient;

use AppBundle\Exception\PayProException;
use Exception;
use GuzzleHttp\Client;

/**
 * Class RequestService
 * @package AppBundle\Service\BitcoinWalletApiClient
 */
class RequestService
{
    protected $bitcoinWalletApiHost;
    protected $httpClient;

    /**
     * @param string $bitcoinWalletApiHost
     * @internal param string $bitcoinWalletSecretKey
     */
    public function __construct(string $bitcoinWalletApiHost) {
        $this->bitcoinWalletApiHost = $bitcoinWalletApiHost;
        $this->httpClient = new Client();
    }

    /**
     * This method calls bitcoin wallet client.
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws PayProException
     */
    public function call(string $method, string $endpoint, array $params) : array
    {
        $payload = json_encode($params, JSON_UNESCAPED_SLASHES);

        try {
            $response = $this->httpClient->request(
                $method,
                $this->bitcoinWalletApiHost.$endpoint,
                [
                    'headers' => [
                        'Content-type' => 'application/json'
                    ],
                    'connect_timeout' => 20,
                    'body' => $payload
                ]
            );

        } catch (Exception $e) {
            throw new PayProException("Bad Request", 400);
//            dump($e->getResponse()->getBody()->getContents());die();
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
