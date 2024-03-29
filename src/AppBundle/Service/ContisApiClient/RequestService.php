<?php

namespace AppBundle\Service\ContisApiClient;

use AppBundle\Exception\PayProException;
use Exception;
use GuzzleHttp\Client;

/**
 * Class RequestService
 * @package AppBundle\Service\ContisApiClient
 */
class RequestService
{
    const CONTIS_TOKEN_KEY = 'payproapi.contis_authentication_token';
    const CONTIS_SECURITY_KEY = 'payproapi.contis_authentication_security_key';
    const TOKEN_EXPIRY_DATE_KEY = 'payproapi.contis_token_expiry_time';

    protected $contisApiHost;
    protected $contisSecretKey;
    protected $session;

    /**
     * @param string $contisApiHost
     * @internal param string $contisSecretKey
     */
    public function __construct(string $contisApiHost) {
        $this->contisApiHost = $contisApiHost;
        $this->httpClient = new Client();
    }

    /**
     * This method authenticates with Contis if needed and create the hash data string and hash required by contis to execute the call.
     * @param string $endpoint
     * @param array $params
     * @param array $requestParams
     * @param string $jsonParametersKey
     * @return array
     */
    public function call(string $endpoint, array $params, array $requestParams = [], string $jsonParametersKey = 'objInfo') : array
    {
        $payload['objReqInfo'] = $requestParams;
        $payload[$jsonParametersKey] = $params;

        $payload = json_encode($payload, JSON_UNESCAPED_SLASHES);
        if ($endpoint != 'Login') {
//            dump($payload);die();
        }
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->contisApiHost.$endpoint,
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

        if ($endpoint = 'Login') {
//            dump($response->getBody()->getContents());die();
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
